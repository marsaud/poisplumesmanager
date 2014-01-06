<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        
    }

    public function indexAction()
    {
        
    }

    public function mainMenuAction()
    {
        $this->view->moduleName = $this->getFrontController()->getRequest()->getModuleName();
    }

}
