<?php

class CashRegister_FrontEndController extends Zend_Controller_Action
{

    public function init()
    {
//        require_once APPLICATION_PATH . '/models/Tax.php';
//        require_once APPLICATION_PATH . '/models/TaxMapper.php';
//        require_once APPLICATION_PATH . '/models/Article.php';
//        require_once APPLICATION_PATH . '/models/ArticleMapper.php';
//        require_once APPLICATION_PATH . '/models/Category.php';
//        require_once APPLICATION_PATH . '/models/CategoryMapper.php';
//        require_once APPLICATION_PATH . '/models/Provider.php';
//        require_once APPLICATION_PATH . '/models/ProviderMapper.php';
//        require_once APPLICATION_PATH . '/models/Promotion.php';
//        require_once APPLICATION_PATH . '/models/PromotionMapper.php';

        require_once APPLICATION_PATH . '/modules/cash-register/models/SoldArticle.php';
        require_once APPLICATION_PATH . '/modules/cash-register/models/Payment.php';
        require_once APPLICATION_PATH . '/modules/cash-register/models/PaymentMapper.php';
        require_once APPLICATION_PATH . '/modules/cash-register/models/CartTrailer.php';

//        $contentSwitch = $this->_helper->getHelper('contextSwitch');
//        /* @var $contentSwitch Zend_Controller_Action_Helper_AjaxContext */
//        $contentSwitch->addActionContext('get-articles', 'xml')
//            ->addActionContext('get-categories', 'xml')
//            ->initContext();
    }

    public function indexAction()
    {
        /* @var $db Zend_Db_Adapter_Pdo_Abstract */
        $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

        $categoryModel = new CategoryMapper($db);
        $this->view->categoryTree = $categoryModel->getCategoryTree();
        $promoModel = new PromotionMapper($db);
        $this->view->promoList = $promoModel->getPromotions();
    }

    public function payAction()
    {
        if (empty($_POST))
        {
            throw new Exception('EMPTY FORM');
        }
        /* @var $db Zend_Db_Adapter_Pdo_Abstract */
        $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

        $articleModel = new ArticleMapper($db);
        $promoModel = new PromotionMapper($db);
        $paymentModel = new PaymentMapper($db);

        $soldArticles = array();
        
        /*
         * We gather articles with their quantities
         */
        foreach (array_keys($_POST) as $key)
        {
            if (substr($key, 0, 6) == 'promo_')
            {
                continue;
            }

            $soldArticle = $articleModel->find($key);
            $soldArticle->quantity = $_POST[$soldArticle->reference];
            $soldArticles[$soldArticle->reference] = $soldArticle;
        }

        /*
         * We override article's promotions if necessary
         */
        foreach ($_POST as $key => $value)
        {
            if (substr($key, 0, 6) == 'promo_')
            {
                $articleRef = substr($key, 6);
                if (!array_key_exists($articleRef, $soldArticles))
                {
                    throw new Exception('ORPHAN PROMO');
                }

                if ($value == '')
                {
                    if ($soldArticles[$articleRef]->promos->count() > 0)
                    {
                        $promo = $soldArticles[$articleRef]->onePromo;
                        $soldArticles[$articleRef]->promos
                                ->offsetUnset($promo);
                    }
                }
                else
                {
                    $soldArticles[$articleRef]->promos[] =
                            $promoModel->find($value);
                }
            }
        }
        
        $cartTrailer = new CartTrailer($db);
        $hash = $cartTrailer->save($soldArticles);

        $totalRawPrice = array();
        $totalSalePrice = 0;
        $totalTax = array();

        $this->_calculateTicket($soldArticles, $totalRawPrice, $totalTax, $totalSalePrice);

        $this->view->hash = $hash;
        $this->view->soldArticles = $soldArticles;
        $this->view->totalRawPrice = $totalRawPrice;
        $this->view->totalSalePrice = $totalSalePrice;
        $this->view->totalTax = $totalTax;
        $this->view->paymentList = $paymentModel->getPayments();
        
        /**
         * 
         * @todo
         * 
         * On crée le numéro de ticket maintenant et on enregistre tout en base
         * de données. On peut le payer plus tard.
         * 
         * Du coup un "ticket" ets pyé ou non.
         * Quand on le paie, on a juste à lier le paiement avec le ticket.
         * Les détails du ticket me permettent de ventiler la TVA des ventes.
         */
    }

    protected function _calculateTicket(array $soldArticles, &$totalRawPrice, &$totalTax, &$totalSalePrice)
    {
        foreach ($soldArticles as $soldArticle)
        {
            $quantity = $soldArticle->quantity;

            isset($totalRawPrice[$soldArticle->tax->ratio])
                    || $totalRawPrice[$soldArticle->tax->ratio] = 0;
            isset($totalTax[$soldArticle->tax->ratio])
                    || $totalTax[$soldArticle->tax->ratio] = 0;

            $totalRawPrice[$soldArticle->tax->ratio] +=
                    $quantity * $soldArticle->getRawPrice();
            $totalSalePrice +=
                    $quantity * $soldArticle->getPromotionPrice();
            $totalTax[$soldArticle->tax->ratio] +=
                    $quantity * $soldArticle->getTaxAmount();
        }
    }
    
    public function getArticlesAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $request = $this->getRequest();

        /* @var $request Zend_Controller_Request_Http */
        if (!$request->isXmlHttpRequest())
        {
            throw new RuntimeException();
        }

        $categoryRef = $request->getParam('category');

        if (NULL != $categoryRef)
        {
            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                    ->getResource('multidb')
                    ->getDb('ppmdb');

            $model = new ArticleMapper($db);
            $articles = $model->getArticles($categoryRef);

            $this->view->content = $this->view->articlePad($articles);
        }
    }

    public function getCategoriesAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $request = $this->getRequest();

        /* @var $request Zend_Controller_Request_Http */
        if (!$request->isXmlHttpRequest())
        {
            throw new RuntimeException();
        }

        $categoryRef = $request->getParam('category');

        /* @var $db Zend_Db_Adapter_Pdo_Abstract */
        $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

        $model = new CategoryMapper($db);
        $tree = $model->getCategoryTree();
        if ($categoryRef !== NULL)
        {
            if (!array_key_exists($categoryRef, $tree))
            {
                throw new RuntimeException();
            }

            $tree = $tree[$categoryRef]->subCategories;
        }

        $this->view->content = $this->view->categoryPad($tree);
    }

    public function registerAction()
    {
        // action body
    }

}

