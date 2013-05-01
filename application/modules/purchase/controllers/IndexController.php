<?php

class Purchase_IndexController extends PurchaseControllerAbstract
{

    /**
     * Main page for the purchase module
     */
    public function indexAction()
    {
        $this->_forward('manage', 'index', 'purchase');
    }

    /**
     * Purchase module menu
     */
    public function menuAction()
    {
        
    }
    
    /**
     * Composed of Create form and Display zone
     * Menu acces
     */
    public function manageAction()
    {
        
    }

    /**
     * Display zone
     */
    public function displayAction()
    {
        $session = new Zend_Session_Namespace('purchase');
        $startDate = new DateTime($session->startDate);
        $endDate = new DateTime($session->endDate);

        $this->view->content = $this->purchaseManager->getPeriod($startDate, $endDate);
    }

    /**
     * Display period form
     */
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

    /**
     * Processing display period choice
     */
    public function displayDateAction()
    {
        $startDate = new DateTime($_POST['startdate']);
        $endDate = new DateTime($_POST['enddate']);

        $session = new Zend_Session_Namespace('purchase');
        $session->startDate = $startDate->format('Y-m-d');
        $session->endDate = $endDate->format('Y-m-d');

        $this->_forward('index', 'index', 'purchase');
    }

    /**
     * Composed of update form
     */
    public function updateAction()
    {
        $this->view->purchaseid = $this->getRequest()->getParam('purchaseid');
    }

}

