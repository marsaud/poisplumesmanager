<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PurchaseDisplay
 *
 * @author fabrice
 */
class Purchase_View_Helper_PurchaseDisplay extends Zend_View_Helper_Abstract
{
    /**
     * 
     * @param Purchase[] $purchases
     * 
     * @return string
     */
    public function purchaseDisplay(array $purchases)
    {
        $table = '';
        
        foreach($purchases as $purchase)
        {
            $table .= '<tr class="purchaseline">' . $this->_displayPurchaseLine($purchase) . '</tr>';
        }
        
        $table = '<table class="table"><tr><th>Objet</th><th>HT</th><th>TVA</th><th>TTC</th><th>Paiement</th><th>Hors-marge</th><th>Date</th><th>Actions</th></tr>' . $table . '</table>';
        
        return $table;
    }
    
    protected function _displayPurchaseLine(Purchase $purchase)
    {
        $line = '';
        
        $line .= '<td>' . $purchase->item . '</td>';
        $line .= '<td>' . $this->view->currency($purchase->priceHT) . '</td>';
        $line .= '<td>' . $purchase->tax . ' %</td>';
        $line .= '<td>' . $this->view->currency($purchase->priceTTC) . '</td>';
        $line .= '<td>' . $purchase->payMode . '</td>';
        $line .= '<td>' . ($purchase->offMargin ? 'X' : '') . '</td>';
        $line .= '<td>'. $purchase->date .'</td>';
        $line .= '<td><a href="'.$this->view->baseUrl('purchase/index/update/purchaseid/' . $purchase->id).'" class="btn btn-xs btn-warning">Modifier</a>';
        $line .= '&nbsp;<a href="'.$this->view->baseUrl('purchase/manage/delete/purchaseid/' . $purchase->id).'" class="confirm btn btn-xs btn-danger">Supprimer</a></td>';
        
        return $line;
    }
}