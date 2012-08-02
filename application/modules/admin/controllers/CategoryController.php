<?php

class Admin_CategoryController extends Zend_Controller_Action
{

    public function init()
    {
        require_once APPLICATION_PATH . '/models/Category.php';
        require_once APPLICATION_PATH . '/models/CategoryMapper.php';
    }

    public function indexAction()
    {
        /* @var $multidb Zend_Db_Adapter_Pdo_Abstract */
        $db = $this->getInvokeArg('bootstrap')
            ->getResource('multidb')
            ->getDb('ppmdb');

        $model = new CategoryMapper($db);
        $categoryTree = $model->getCategoryTree();

        $this->view->categoryTree = $categoryTree;
    }

    public function createAction()
    {
        if (!empty($_POST))
        {
            $category = new Category();
            $category->reference = $_POST['categoryref'];
            $category->name = $_POST['categoryname'];
            $category->description = $_POST['categorydesc'];

            $parentReference = $_POST['parentcategory'] != '' ? $_POST['parentcategory'] : NULL;

            /* @var $multidb Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

            $model = new CategoryMapper($db);
            $model->insert($category, $parentReference);
        }
        $this->_forward('index');
    }

    public function updateAction()
    {
        if (!empty($_POST))
        {
            $category = new Category();
            $category->reference = $_POST['modcategoryref'];
            $category->name = $_POST['modcategoryname'];
            $category->description = $_POST['modcategorydesc'];

            $parentReference = $_POST['modparentcategory'] != '' ? $_POST['modparentcategory'] : NULL;

            /* @var $multidb Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

            $model = new CategoryMapper($db);
            $model->update($category, $parentReference);
        }
        $this->_forward('index');
    }

}

