<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Purchase_ManageContoller
 *
 * @author fabrice
 */
class Purchase_ManageController extends PurchaseControllerAbstract
{
    /**
     * Creates from a submission and forwards to default action
     */
    public function createAction()
    {
        if (!empty($_POST))
        {
            $date = new DateTime($_POST['purchasedate']);
            $purchase = new Purchase();
            $purchase->date = $date->format('Y-m-d');
            $purchase->item = $_POST['purchaseitem'];
            $purchase->offMargin = isset($_POST['offmargin']);
            $purchase->payMode = $_POST['purchasemode'];
            $purchase->priceHT = $_POST['purchaseht'];
            $purchase->priceTTC = $_POST['purchasettc'];
            $purchase->tax = $_POST['purchasetax'];

            $purchaseManager = $this->purchaseManager;
            $purchaseManager->create($purchase);

            $_POST = array();

            $this->_forward('index', 'index', 'purchase');
        }
    }

    /**
     * Updates from a submission and forwards to default action
     */
    public function updateAction()
    {
        $purchase = new Purchase();
        $purchase->id = $_POST['purchaseid'];
        $purchase->date = $_POST['purchasedate'];
        $purchase->item = $_POST['purchaseitem'];
        $purchase->offMargin = isset($_POST['offmargin']);
        $purchase->payMode = $_POST['purchasemode'];
        $purchase->priceHT = $_POST['purchaseht'];
        $purchase->priceTTC = $_POST['purchasettc'];
        $purchase->tax = $_POST['purchasetax'];
        
        $this->purchaseManager->update($purchase);
        
        $this->_forward('index', 'index', 'purchase');
    }

    /**
     * Deletes from a submission and forward to default action
     */
    public function deleteAction()
    {
        $purchaseManager = $this->purchaseManager;
        
        $purchase = $purchaseManager->get($this->getRequest()->getParam('purchaseid'));
        $purchaseManager->delete($purchase);
        
        $this->_forward('index', 'index', 'purchase');
    }

    /**
     * The creation form
     */
    public function createFormAction()
    {
        
    }

    /**
     * The update form
     */
    public function updateFormAction()
    {
        $purchase = $this->purchaseManager->get($this->getRequest()->getParam('purchaseid'));
        $this->view->purchase = $purchase;
    }

}