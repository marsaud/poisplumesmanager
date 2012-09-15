<?php

class Admin_ArticleController extends Zend_Controller_Action
{

    /**
     *
     * @var CategoryMapper
     */
    protected $_categoryModel;

    /**
     *
     * @var PromotionMapper
     */
    protected $_promotionModel;

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

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        /* @var $ajaxContext Zend_Controller_Action_Helper_AjaxContext */
        $ajaxContext->addActionContext('get', 'json')
            ->initContext();
    }

    public function indexAction()
    {
        /* @var $db Zend_Db_Adapter_Pdo_Abstract */
        $db = $this->getInvokeArg('bootstrap')
            ->getResource('multidb')
            ->getDb('ppmdb');

        $taxModel = new TaxMapper($db);
        $this->view->taxList = $taxModel->getTaxes();

        $categoryModel = new CategoryMapper($db);
        $this->view->categoryTree = $categoryModel->getCategoryTree();

        $providerModel = new ProviderMapper($db);
        $this->view->providerList = $providerModel->getProviders();

        $promoModel = new PromotionMapper($db);
        $this->view->promoList = $promoModel->getPromotions();

        $articleModel = new ArticleMapper($db);
        $this->view->articleList = $articleModel->getArticles();
    }

    public function createAction()
    {
        if (isset($_POST))
        {
            $article = new Article();

            $article->reference = $_POST['ref'];
            $article->name = $_POST['name'];
            $article->description = $_POST['desc'];
            $article->price = $_POST['priceht'];
            $article->stock = isset($_POST['stock']);

            if ($article->stock)
            {
                $article->unit = $_POST['unit'];
            }

            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

            $taxModel = new TaxMapper($db);
            $article->tax = $taxModel->find($_POST['tva']);

            if ($_POST['provider'] != 0)
            {
                $providerModel = new ProviderMapper($db);
                $article->provider = $providerModel->find($_POST['provider']);
            }

            if (isset($_POST['cat']))
            {
                foreach ($_POST['cat'] as $ref)
                {
                    $category = $this->_getCategoryModel($db)->find($ref);
                    $article->categories[] = $category;
                }
            }

            if ($_POST['promo'] != '')
            {
                $promo = $this->_getPromotionModel($db)->find($_POST['promo']);
                $article->promos[] = $promo;
            }

            $articleModel = new ArticleMapper($db);
            $articleModel->create($article);
        }

        $this->_forward('index');
    }

    public function updateAction()
    {
        if (isset($_POST))
        {
            $article = new Article();

            $article->reference = $_POST['modref'];
            $article->name = $_POST['modname'];
            $article->description = $_POST['moddesc'];
            $article->price = $_POST['modpriceht'];
            $article->stock = isset($_POST['modstock']);

            if ($article->stock)
            {
                $article->unit = $_POST['modunit'];
            }

            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

            $taxModel = new TaxMapper($db);
            $article->tax = $taxModel->find($_POST['modtva']);

            if ($_POST['modprovider'] != 0)
            {
                $providerModel = new ProviderMapper($db);
                $article->provider = $providerModel->find($_POST['modprovider']);
            }

            if (isset($_POST['modcat']))
            {
                foreach ($_POST['modcat'] as $ref)
                {
                    $category = $this->_getCategoryModel($db)->find($ref);
                    $article->categories[] = $category;
                }
            }

            if ($_POST['modpromo'] != '')
            {
                $promo = $this->_getPromotionModel($db)->find($_POST['modpromo']);
                $article->promos[] = $promo;
            }

            $articleModel = new ArticleMapper($db);
            $articleModel->update($article);
        }

        $this->_forward('index');
    }

    /**
     *
     * @param Zend_Db_Adapter_Pdo_Abstract $db
     *
     * @return CategoryMapper
     */
    protected function _getCategoryModel(Zend_Db_Adapter_Pdo_Abstract $db)
    {
        if (NULL === $this->_categoryModel)
        {
            $this->_categoryModel = new CategoryMapper($db);
        }

        return $this->_categoryModel;
    }

    /**
     *
     * @param Zend_Db_Adapter_Pdo_Abstract $db
     *
     * @return PromotionMapper
     */
    protected function _getPromotionModel(Zend_Db_Adapter_Pdo_Abstract $db)
    {
        if (NULL === $this->_promotionModel)
        {
            $this->_promotionModel = new PromotionMapper($db);
        }

        return $this->_promotionModel;
    }

    public function getAction()
    {
        $request = $this->getRequest();
        /* @var $request Zend_Controller_Request_Http */
        if (!$request->isXmlHttpRequest())
        {
            throw new RuntimeException();
        }

        $ref = $request->getParam('ref');

        if (NULL != $ref)
        {
            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

            $model = new ArticleMapper($db);
            $article = $model->find($ref);

            $this->view->name = $article->name;
            $this->view->description = $article->description;
            $this->view->priceht = $article->price;
            $this->view->stock = $article->stock;
            $this->view->tax = $article->tax->id;
            $this->view->unit = $article->unit;

            $categories = array();
            foreach ($article->categories as $category)
            {
                $categories[] = $category->reference;
            }
            $this->view->categories = $categories;

            $promotions = array();
            foreach ($article->promos as $promo)
            {
                $promotions[] = $promo->id;
            }
            $this->view->promos = $promotions;

            if (NULL !== $article->provider)
            {
                $this->view->provider = $article->provider->id;
            }
            else
            {
                $this->view->provider = 0;
            }
        }
    }

}

