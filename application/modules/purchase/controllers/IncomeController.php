<?php

class Purchase_IncomeController extends PurchaseControllerAbstract
{

    public function indexAction()
    {
        $this->_forward('manage', 'income', 'purchase');
    }

    public function manageAction()
    {
        
    }

    /**
     * The creation form
     */
    public function createFormAction()
    {
        
    }

    /**
     * Creates from a submission and forwards to default action
     */
    public function createAction()
    {
        if (!empty($_POST))
        {
            $date = new DateTime($_POST['incomedate']);
            $income = new Income();
            $income->date = $date->format('Y-m-d');
            $income->item = $_POST['incomeitem'];
            $income->payMode = $_POST['incomemode'];
            $income->priceTTC = $_POST['incomepricettc'];

            $this->incomeManager->create($income);

            $_POST = array();

            $this->_forward('index', 'income', 'purchase');
        }
    }

    /**
     * Processing display period choice
     */
    public function displayDateAction()
    {
        $startDate = new DateTime($_POST['startdate']);
        $endDate = new DateTime($_POST['enddate']);

        $session = new Zend_Session_Namespace('income');
        $session->startDate = $startDate->format('Y-m-d');
        $session->endDate = $endDate->format('Y-m-d');

        $this->_forward('index', 'income', 'purchase');
    }

    /**
     * Display period form
     */
    public function displayFormAction()
    {
        $session = new Zend_Session_Namespace('income');
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
     * Display zone
     */
    public function displayAction()
    {
        $session = new Zend_Session_Namespace('income');
        $startDate = new DateTime($session->startDate);
        $endDate = new DateTime($session->endDate);

        $this->view->content = $this->incomeManager->getPeriod($startDate, $endDate);
    }

    /**
     * Updates from a submission and forwards to default action
     */
    public function updateAction()
    {
        $date = new DateTime($_POST['incomedate']);
        
        $income = new Income();
        $income->id = $_POST['incomeid'];
        $income->date = $date->format('Y-m-d');
        $income->item = $_POST['incomeitem'];
        $income->payMode = $_POST['incomemode'];
        $income->priceTTC = $_POST['incomepricettc'];

        $this->incomeManager->update($income);

        $this->_forward('index', 'income', 'purchase');
    }

    /**
     * Composed of update form
     */
    public function updateScreenAction()
    {
        $this->view->incomeid = $this->getRequest()->getParam('incomeid');
    }

    /**
     * The update form
     */
    public function updateFormAction()
    {
        $income = $this->incomeManager->get($this->getRequest()->getParam('incomeid'));
        $this->view->income = $income;
    }

    /**
     * Deletes from a submission and forward to default action
     */
    public function deleteAction()
    {
        $incomeManager = $this->incomeManager;

        $income = $incomeManager->get($this->getRequest()->getParam('incomeid'));
        $incomeManager->delete($income);

        $this->_forward('index', 'income', 'purchase');
    }

}

