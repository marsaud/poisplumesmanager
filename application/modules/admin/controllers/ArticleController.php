<?php

class Admin_ArticleController extends AdminControllerAbstract
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        /* @var $ajaxContext Zend_Controller_Action_Helper_AjaxContext */
        $ajaxContext->addActionContext('get', 'json')
                ->initContext();
    }

    public function indexAction()
    {
        $this->view->taxList = $this->taxMapper->getTaxes();
        $this->view->categoryTree = $this->categoryMapper->getCategoryTree();
        $this->view->providerList = $this->providerMapper->getProviders();
        $this->view->promoList = $this->promotionMapper->getPromotions();
        $this->view->articleList = $this->articleMapper->getArticles();
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
            $article->stockedQuantity = 0;

            if ($article->stock)
            {
                $article->unit = $_POST['unit'];
            }

            $article->tax = $this->taxMapper->find($_POST['tva']);

            if ($_POST['provider'] != 0)
            {
                $article->provider = $this->providerMapper->find($_POST['provider']);
            }

            if (isset($_POST['cat']))
            {
                foreach ($_POST['cat'] as $ref)
                {
                    $category = $this->categoryMapper->find($ref);
                    $article->categories[] = $category;
                }
            }

            if ($_POST['promo'] != '')
            {
                $promo = $this->promotionMapper->find($_POST['promo']);
                $article->promos[] = $promo;
            }

            $this->db->beginTransaction();
            try
            {
                $this->articleMapper->create($article);
                $this->db->commit();
            }
            catch (Exception $exc)
            {
                $this->db->rollBack();
                throw $exc;
            }
        }

        $this->_forward('index');
    }

    public function updateAction()
    {
        if (isset($_POST))
        {
            $article = $this->articleMapper->find($_POST['modref']);

            $article->name = $_POST['modname'];
            $article->description = $_POST['moddesc'];
            $article->price = $_POST['modpriceht'];
            $article->stock = isset($_POST['modstock']);

            if ($article->stock)
            {
                $article->unit = $_POST['modunit'];
            }

            $article->tax = $this->taxMapper->find($_POST['modtva']);

            if ($_POST['modprovider'] != 0)
            {
                $article->provider = $this->providerMapper->find($_POST['modprovider']);
            }

            if (isset($_POST['modcat']))
            {
                foreach ($_POST['modcat'] as $ref)
                {
                    $category = $this->categoryMapper->find($ref);
                    $article->categories[] = $category;
                }
            }

            if ($_POST['modpromo'] != '')
            {
                $promo = $this->promotionMapper->find($_POST['modpromo']);
                $article->promos[] = $promo;
            }

            $this->db->beginTransaction();
            try
            {
                $this->articleMapper->update($article);
                $this->db->commit();
            }
            catch (Exception $exc)
            {
                $this->db->rollBack();
                throw $exc;
            }
        }

        $this->_forward('index');
    }

    public function getAction()
    {
        $request = $this->getRequest();
        /* @var $request Zend_Controller_Request_Http */
        if (!$request->isXmlHttpRequest())
        {
            throw new RuntimeException('Wrong Request Context');
        }

        $ref = $request->getParam('ref');

        if (NULL != $ref)
        {
            $article = $this->articleMapper->find($ref);

            $this->view->name = $article->name;
            $this->view->description = $article->description;
            $this->view->priceht = $article->getSalePrice();
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

