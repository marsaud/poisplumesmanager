<?php

class Admin_CategoryController extends Zend_Controller_Action
{

    public function init()
    {
        require_once APPLICATION_PATH . '/models/Category.php';
        require_once APPLICATION_PATH . '/models/CategoryMapper.php';

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

            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
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

            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

            $model = new CategoryMapper($db);
            $model->update($category, $parentReference);
        }
        $this->_forward('index');
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

            $model = new CategoryMapper($db);
            $category = $model->find($ref);
            $parent = $model->findParent($category);

            $this->view->reference = $category->reference;
            $this->view->name = $category->name;
            $this->view->description = $category->description;

            $this->view->parentReference = $parent !== NULL ?
                $parent->reference : '';
        }
    }

}

