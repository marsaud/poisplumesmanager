<?php

class Admin_ArticleController extends Zend_Controller_Action
{

    public function init()
    {
        require_once APPLICATION_PATH . '/models/Tax.php';
        require_once APPLICATION_PATH . '/models/TaxMapper.php';
        require_once APPLICATION_PATH . '/models/Article.php';
        require_once APPLICATION_PATH . '/models/Category.php';
        require_once APPLICATION_PATH . '/models/CategoryMapper.php';
        require_once APPLICATION_PATH . '/models/Provider.php';
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
        $categoryTree = $categoryModel->getCategoryTree();

        $this->view->categoryTree = $categoryTree;

        $providerList = array();
        $p = new Provider();
        $p->id = 1;
        $p->name = 'La ferme aux lapins';
        $providerList[] = $p;
        $p = new Provider();
        $p->id = 2;
        $p->name = 'Le verger aux abris côtiers';
        $providerList[] = $p;
        $p = new Provider();
        $p->id = 3;
        $p->name = 'L\'atelier des fous à lier';
        $providerList[] = $p;

        $this->view->providerList = $providerList;

        reset($this->view->taxList);
        $articleList = array();
        $a = new Article();
        $a->reference = 'abc';
        $a->name = 'ABC';
        $a->description = 'L\'alphabet en 3 lettres';
        $a->price = 100;
        $a->tax = current($this->view->taxList);
        $a->categories = array(current($this->view->categoryTree));
        $articleList[] = $a;
        $a = new Article();
        $a->reference = 'def';
        $a->name = 'DEF';
        $a->description = 'La suite de l\'alphabet';
        $a->price = 60;
        $a->tax = current($this->view->taxList);
        $a->categories = array(current($this->view->categoryTree));
        $articleList[] = $a;

        $this->view->articleList = $articleList;
    }

}

