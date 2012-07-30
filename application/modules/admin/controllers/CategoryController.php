<?php

class Admin_CategoryController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        require_once APPLICATION_PATH . '/models/Category.php';

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

