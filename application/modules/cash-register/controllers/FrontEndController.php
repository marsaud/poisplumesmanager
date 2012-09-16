<?php

class CashRegister_FrontEndController extends Zend_Controller_Action
{

    public function init()
    {
        require_once APPLICATION_PATH . '/models/Tax.php';
        require_once APPLICATION_PATH . '/models/TaxMapper.php';
        require_once APPLICATION_PATH . '/models/Article.php';
        require_once APPLICATION_PATH . '/models/ArticleMapper.php';
        require_once APPLICATION_PATH . '/models/Category.php';
        require_once APPLICATION_PATH . '/models/CategoryMapper.php';
        require_once APPLICATION_PATH . '/models/Provider.php';
        require_once APPLICATION_PATH . '/models/ProviderMapper.php';
        require_once APPLICATION_PATH . '/models/Promotion.php';
        require_once APPLICATION_PATH . '/models/PromotionMapper.php';

        require_once APPLICATION_PATH . '/modules/cash-register/models/SoldArticle.php';

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

    public function registerAction()
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
        $soldArticles = array();

        foreach (array_keys($_POST) as $key)
        {
            if ($key == 'submit' || substr($key, 0, 6) == 'promo_')
            {
                continue;
            }

            $soldArticles[$key] = new SoldArticle();
            $soldArticles[$key]->article = $articleModel->find($key);
        }

        foreach ($_POST as $key => $value)
        {
            if (substr($key, 0, 6) == 'promo_')
            {
                $articleRef = substr($key, 6);
                if (!array_key_exists($articleRef, $soldArticles))
                {
                    throw new Exception('ORPHAN PROMO');
                }

                $soldArticles[$articleRef]->article->promos = array(
                    $promoModel->find($value)
                );
            }
        }

        $totalRawPrice = array();
        $totalSalePrice = 0;
        $totalTax = array();

        foreach ($soldArticles as $soldArticle)
        {
            $quantity = $_POST[$soldArticle->article->reference];

            isset($totalRawPrice[$soldArticle->article->tax->ratio])
                    || $totalRawPrice[$soldArticle->article->tax->ratio] = 0;
            isset($totalTax[$soldArticle->article->tax->ratio])
                    || $totalTax[$soldArticle->article->tax->ratio] = 0;

            $totalRawPrice[$soldArticle->article->tax->ratio] +=
                    $quantity * $soldArticle->article->getRawPrice();
            $totalSalePrice +=
                    $quantity * $soldArticle->article->getPromotionPrice();
            $totalTax[$soldArticle->article->tax->ratio] +=
                    $quantity * $soldArticle->article->getTaxAmount();

            $soldArticle->quantity = $quantity;
        }

        $this->view->soldArticles = $soldArticles;
        $this->view->totalRawPrice = $totalRawPrice;
        $this->view->totalSalePrice = $totalSalePrice;
        $this->view->totalTax = $totalTax;
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

}

