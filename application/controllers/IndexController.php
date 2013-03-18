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
        
    }
    
    /**
     * @todo A footer to recall the exploitation environment in front-end
     */
    public function footerAction()
    {
        /* @var $bootstrap Zend_Application_Bootstrap_Bootstrap */
        $bootstrap = $this->getInvokeArg('bootstrap');
        $this->view->dbhost;
    }
}

