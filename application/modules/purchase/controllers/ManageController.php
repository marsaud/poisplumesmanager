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

    public function createAction()
    {
        if (!empty($_POST))
        {
            $date = new DateTime($_POST['purchasedate']);
            $purchase = new Purchase();
            $purchase->date = $date->format('Y-m-d H:i:s');
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

    public function updateAction()
    {
        $this->_forward('index', 'index', 'purchase');
    }

    public function deleteAction()
    {
        $this->_forward('index', 'index', 'purchase');   
    }

    public function createFormAction()
    {
        
    }

    public function updateFormAction()
    {
        $purchase = new Purchase();
        
        $purchase->date = '2013-01-14 21:45:00';
        $purchase->id = 99;
        $purchase->item = 'Taratata';
        $purchase->offMargin = true;
        $purchase->payMode = 'chq';
        $purchase->priceHT = '5.87';
        $purchase->priceTTC = '6.99';
        $purchase->tax = 7;
        
        $this->view->purchase = $purchase;
    }

}