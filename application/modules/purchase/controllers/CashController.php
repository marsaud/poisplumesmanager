<?php

class Purchase_CashController extends PurchaseControllerAbstract
{
    public function indexAction()
    {
        
    }

    public function insertFormAction()
    {
        
    }

    public function updateFormAction()
    {
        
    }

    public function insertAction()
    {
        $this->_forward('index', 'cash', 'purchase');
    }

    public function updateAction()
    {
        $this->_forward('index', 'cash', 'purchase');
    }

    public function deleteAction()
    {
        $this->_forward('index', 'cash', 'purchase');
    }

    public function displayFormAction()
    {
        $session = new Zend_Session_Namespace('cash');
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

        $session = new Zend_Session_Namespace('cash');
        $session->startDate = $startDate->format('Y-m-d');
        $session->endDate = $endDate->format('Y-m-d');

        $this->_forward('index', 'cash', 'purchase');
    }

    public function displayAction()
    {
        $cashState = $this->cashManager->getState('');
        $this->view->content = $cashState;
    }
}

