<?php

class Purchase_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        require_once APPLICATION_PATH . '/modules/purchase/models/Purchase.php';
        require_once APPLICATION_PATH . '/modules/purchase/models/PurchaseManager.php';
    }

    public function indexAction()
    {
        // action body
    }

    public function menuAction()
    {
        
    }
    
    public function displayAction()
    {
        /* @var $db Zend_Db_Adapter_Pdo_Abstract */
        $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');
        
        $pm = new PurchaseManager($db);
        
        $this->view->content = $pm->get(new Zend_Date, new Zend_Date); 
    }
}

