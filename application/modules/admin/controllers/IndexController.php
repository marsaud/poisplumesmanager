<?php

class Admin_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        
    }

    public function indexAction()
    {
        
    }

    public function menuAction()
    {
        $this->view->controllerName = $this->getFrontController()->getRequest()->getControllerName();
    }

}

