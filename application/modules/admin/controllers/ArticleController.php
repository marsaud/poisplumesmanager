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
        $taxList = $this->taxMapper->getTaxes();
        $categoryTree = $this->categoryMapper->getCategoryTree();
        $providerList = $this->providerMapper->getProviders();
        $promoList = $this->promotionMapper->getPromotions();
        $articleList = $this->articleMapper->getArticles();

        $this->view->articleList = $articleList;

        $this->view->placeholder('forms')->create = $this->view->action(
                'create-form', 'article', 'admin', array(
            'taxList' => $taxList,
            'promoList' => $promoList,
            'categoryTree' => $categoryTree,
            'providerList' => $providerList
        ));

        $this->view->placeholder('forms')->update = $this->view->action('update-form', 'article', 'admin', array(
            'taxList' => $taxList,
            'promoList' => $promoList,
            'categoryTree' => $categoryTree,
            'providerList' => $providerList,
            'articleList' => $articleList
        ));
    }

    public function createFormAction()
    {
        $this->view->taxList = $this->getRequest()->getParam('taxList');
        $this->view->promoList = $this->getRequest()->getParam('promoList');
        $this->view->categoryTree = $this->getRequest()->getParam('categoryTree');
        $this->view->providerList = $this->getRequest()->getParam('providerList');
    }

    public function updateFormAction()
    {
        $this->view->taxList = $this->getRequest()->getParam('taxList');
        $this->view->promoList = $this->getRequest()->getParam('promoList');
        $this->view->categoryTree = $this->getRequest()->getParam('categoryTree');
        $this->view->providerList = $this->getRequest()->getParam('providerList');
        $this->view->articleList = $this->getRequest()->getParam('articleList');
    }

    public function createAction()
    {
        $request = $this->getRequest();
        /* @var $request Zend_Controller_Request_Http */

        if ($request->isPost())
        {
            $article = new Article();

            $article->reference = $request->ref;
            $article->name = $request->name;
            $article->description = $request->desc;
            $article->price = $request->priceht;
            $article->stock = isset($request->stock);
            $article->stockedQuantity = 0;

            if ($article->stock)
            {
                $article->unit = $request->unit;
            }

            $article->tax = $this->taxMapper->find($request->tva);

            if ($request->provider != 0)
            {
                $article->provider = $this->providerMapper->find($request->provider);
            }

            if (isset($request->cat))
            {
                $this->_loadCategories($request->cat, $article);
            }

            if ($request->promo != '')
            {
                $promo = $this->promotionMapper->find($request->promo);
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
        $request = $this->getRequest();
        /* @var $request Zend_Controller_Request_Http */

        if ($request->isPost())
        {
            $article = $this->articleMapper->find($request->modref);

            $article->name = $request->modname;
            $article->description = $request->moddesc;
            $article->price = $request->modpriceht;
            $article->stock = isset($request->modstock);

            if ($article->stock)
            {
                $article->unit = $request->modunit;
            }
            else
            {
                $article->stockedQuantity = 0;
            }

            $article->tax = $this->taxMapper->find($request->modtva);

            if ($request->modprovider != 0)
            {
                $article->provider = $this->providerMapper->find($request->modprovider);
            }
            else
            {
                $article->provider = NULL;
            }

            $article->freeCategories();
            if (isset($request->modcat))
            {
                $this->_loadCategories($request->modcat, $article);
            }

            $article->freePromotions();
            if ($request->modpromo != '')
            {
                $promo = $this->promotionMapper->find($request->modpromo);
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

    /**
     * 
     * @param integer[] $categoryRefs
     * @param Article $article
     * 
     * @return Article
     */
    protected function _loadCategories($categoryRefs, $article)
    {
        foreach ($categoryRefs as $ref)
        {
            $category = $this->categoryMapper->find($ref);
            $article->categories[] = $category;
        }
        
        return $article;
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
            $this->view->priceht = $article->getFrontPrice();
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
