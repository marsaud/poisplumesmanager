<?php

class Stock_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        require_once APPLICATION_PATH . '/models/Category.php';
        require_once APPLICATION_PATH . '/models/Article.php';
        require_once APPLICATION_PATH . '/models/Tax.php';
    }

    public function indexAction()
    {
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

        $tva = new Tax();
        $tva->id = 2;
        $tva->name = 'Généraliste';
        $tva->ratio = 19.6;

        $articleList = array();
        $a = new Article();
        $a->reference = 'abc';
        $a->name = 'ABC';
        $a->description = 'L\'alphabet en 3 lettres';
        $a->price = 100;
        $a->tax = $tva;
        $a->categories = array($c);
        $articleList[] = $a;
        $a = new Article();
        $a->reference = 'def';
        $a->name = 'DEF';
        $a->description = 'La suite de l\'alphabet';
        $a->price = 60;
        $a->tax = $tva;
        $a->categories = array($c);
        $articleList[] = $a;

        $this->view->articleList = $articleList;
    }

}

