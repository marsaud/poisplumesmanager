<?php

class CashRegister_IndexController extends AbstractControllerAbstract
{

    public function init()
    {
        require_once APPLICATION_PATH . '/modules/cash-register/models/Payment.php';
        require_once APPLICATION_PATH . '/modules/cash-register/models/PaymentMapper.php';
        require_once APPLICATION_PATH . '/modules/cash-register/models/CartTrailer.php';
        require_once APPLICATION_PATH . '/modules/cash-register/models/OperationManager.php';
        require_once APPLICATION_PATH . '/modules/cash-register/models/OperationMapper.php';
        require_once APPLICATION_PATH . '/modules/stock/models/StockManager.php';
    }

    public function indexAction()
    {
        $this->view->categoryTree = $this->categoryMapper->getCategoryTree();
        $this->view->promoList = $this->promotionMapper->getPromotions();
    }

    public function payAction()
    {
        if (empty($_POST))
        {
            throw new Exception('EMPTY FORM');
        }

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

            $soldArticle = $this->articleMapper->find($key);
            $soldArticle->soldQuantity = $_POST[$soldArticle->reference];
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
                            $this->promotionMapper->find($value);
                }
            }
        }

        $hash = $this->cartTrailer->save($soldArticles);

        $totalRawPrice = array();
        $totalSalePrice = 0;
        $totalTax = array();

        $operationManager = new OperationManager();
        $operationManager->compute($soldArticles, $totalRawPrice, $totalTax, $totalSalePrice);

        $this->view->hash = $hash;
        $this->view->soldArticles = $soldArticles;
        $this->view->totalRawPrice = $totalRawPrice;
        $this->view->totalSalePrice = $totalSalePrice;
        $this->view->totalTax = $totalTax;
        $this->view->paymentList = $this->paymentMapper->get();

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

    /**
     * 
     * @param Article[] $soldArticles
     * @param float $totalRawPrice
     * @param float $totalTax
     * @param float $totalSalePrice
     */
    protected function _calculateTicket(array $soldArticles, &$totalRawPrice, &$totalTax, &$totalSalePrice)
    {
        /* @var $soldArticle Article */
        foreach ($soldArticles as $soldArticle)
        {
            $quantity = $soldArticle->soldQuantity;

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
            $articles = $this->articleMapper->getArticles($categoryRef);
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

        $tree = $this->categoryMapper->getCategoryTree();
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
        if (empty($_POST))
        {
            throw new Exception('EMPTY FORM');
        }

        $articles = $this->cartTrailer->get($_POST['hash']);
        $payments = $this->paymentMapper->get();

        foreach ($payments as $payment)
        {
            /* @var $payment Payment */
            if (isset($_POST[$payment->reference . 'given']))
            {
                $payment->percieved = $_POST[$payment->reference . 'given'];
            }
            if (isset($_POST[$payment->reference . 'returned']))
            {
                $payment->returned = $_POST[$payment->reference . 'returned'];
            }
        }

        $this->db->beginTransaction();
        try
        {
            foreach ($articles as $article)
            {
                if ($article->stock)
                {
                    $this->stockManager->modify($article->reference, -($article->soldQuantity), $_POST['hash']);
                }
            }

            $this->operationMapper->record($_POST['hash'], $articles, $payments);
            
            $this->db->commit();
        }
        catch (Exception $exc)
        {
            $this->db->rollBack();
            throw $exc;
        }

    }

}

