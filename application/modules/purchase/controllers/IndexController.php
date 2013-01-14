<?php

class Purchase_IndexController extends PurchaseControllerAbstract
{

    public function indexAction()
    {
        
    }

    public function menuAction()
    {
        
    }

    public function displayAction()
    {
        $session = new Zend_Session_Namespace('purchase');
        $startDate = new DateTime($session->startDate);
        $endDate = new DateTime($session->endDate);

        /* @var $db Zend_Db_Adapter_Pdo_Abstract */
        $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

        $pm = new PurchaseManager($db);

        $this->view->content = $pm->get($startDate, $endDate);
    }

    public function displayFormAction()
    {
        $session = new Zend_Session_Namespace('purchase');
        if (!isset($session->startDate))
        {
            $date = new DateTime();
            $session->startDate = $date->format('Y-m-d');
            $date->modify('+1 day');
            $session->endDate = $date->format('Y-m-d');
        }
        $this->view->startDate = $session->startDate;
        $this->view->endDate = $session->endDate;
    }

    public function displayDateAction()
    {
        $startDate = new DateTime($_POST['startdate']);
        $endDate = new DateTime($_POST['enddate']);

        $session = new Zend_Session_Namespace('purchase');
        $session->startDate = $startDate->format('Y-m-d');
        $session->endDate = $endDate->format('Y-m-d');

        $this->_forward('index', 'index', 'purchase');
    }
    
    public function updateAction()
    {
        
    }
}

