<?php

class Admin_ArticleController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        require_once APPLICATION_PATH . '/models/Tax.php';
        require_once APPLICATION_PATH . '/models/Article.php';
        require_once APPLICATION_PATH . '/models/Category.php';

        $this->view->taxList = array();
        $tva = new Tax();
        $tva->id = 1;
        $tva->name = 'Non applicable';
        $tva->ratio = 0;
        $this->view->taxList[] = $tva;
        $tva = new Tax();
        $tva->id = 2;
        $tva->name = 'Généraliste';
        $tva->ratio = 19.6;
        $this->view->taxList[] = $tva;

        $categoryTree = array();

        $c = new Category();
        $c->reference = 'resto';
        $c->name = 'Restaurant';
        $categoryTree[] = $c;

        $sc = new Category();
        $sc->reference = 'entree';
        $sc->name = 'Entrée / Salades';
        $c[] = $sc;
        $sc = new Category();
        $sc->reference = 'plat';
        $sc->name = 'Plats cuisinés';
        $c[] = $sc;

        $c = new Category();
        $c->reference = 'boutique';
        $c->name = 'Boutique';
        $categoryTree[] = $c;

        $this->view->categoryTree = $categoryTree;
    }

}

