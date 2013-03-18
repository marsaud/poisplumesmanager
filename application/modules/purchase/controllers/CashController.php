<?php

class Purchase_CashController extends PurchaseControllerAbstract
{
    public function indexAction()
    {
        // action body
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
        
    }

    public function displayDateAction()
    {
        
    }

    public function displayAction()
    {
        $cashState = $this->cashManager->getState('');
        $this->view->content = $cashState;
    }
}

