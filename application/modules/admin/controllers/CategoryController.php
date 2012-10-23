<?php

class Admin_CategoryController extends AdminControllerAbstract
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
        $categoryTree = $this->categoryMapper->getCategoryTree();
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

            $parentReference = ($_POST['parentcategory'] != '') ?
                    $_POST['parentcategory'] : NULL;

            $this->categoryMapper->insert($category, $parentReference);
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

            $parentReference = ($_POST['modparentcategory'] != '') ?
                    $_POST['modparentcategory'] : NULL;

            $this->categoryMapper->update($category, $parentReference);
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
            $category = $this->categoryMapper->find($ref);
            $parent = $this->categoryMapper->findParent($category);

            $this->view->reference = $category->reference;
            $this->view->name = $category->name;
            $this->view->description = $category->description;

            $this->view->parentReference = ($parent !== NULL) ?
                    $parent->reference : '';
        }
    }

}

