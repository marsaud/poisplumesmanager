<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        
    }

    public function indexAction()
    {
        $this->_forward('index', 'index', 'cash-register');
    }

    public function mainMenuAction()
    {
        $this->view->moduleName = $this->getFrontController()->getRequest()->getModuleName();
    }

}
