<?php

class Stock_IndexController extends Zend_Controller_Action
{

    public function init()
    {
//        require_once APPLICATION_PATH . '/models/Category.php';
//        require_once APPLICATION_PATH . '/models/Article.php';
//        require_once APPLICATION_PATH . '/models/Tax.php';
    }

    public function indexAction()
    {

        $selectedCategory = (!empty($_POST['categoryfilter']) ?
                        $_POST['categoryfilter'] : NULL);

        /* @var $db Zend_Db_Adapter_Pdo_Abstract */
        $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

        $categoryModel = new CategoryMapper($db);
        $this->view->categoryTree = $categoryModel->getCategoryTree();

        $articleModel = new ArticleMapper($db);
        $this->view->articleList = $articleModel->getArticles($selectedCategory, true);

        $this->view->selectedCategory = $selectedCategory;
    }

}

