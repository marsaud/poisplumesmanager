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
            $table .= '<tr>' . $this->_displayPurcahseLine($purchase) . '</tr>';
        }
        
        $table = '<table><tr><th>Objet</th><th>HT</th><th>TVA</th><th>TTC</th><th>Paiement</th><th>Hors-marge</th><th>Actions</th></tr>' . $table . '</table>';
        
        return $table;
    }
    
    protected function _displayPurcahseLine(Purchase $purchase)
    {
        $line = '';
        
        $line .= '<td>' . $purchase->item . '</td>';
        $line .= '<td>' . $this->view->currency($purchase->priceHT) . '</td>';
        $line .= '<td>' . $purchase->tax . ' %</td>';
        $line .= '<td>' . $this->view->currency($purchase->priceTTC) . '</td>';
        $line .= '<td>' . $purchase->payMode . '</td>';
        $line .= '<td>' . ($purchase->offMargin ? 'X' : '') . '</td>';
        $line .= '<td><a href="'.$this->view->baseUrl('purchase/index/update/id/' . $purchase->id).'">Modifier</a>';
        $line .= '&nbsp;<a href="'.$this->view->baseUrl('purchase/manage/delete/id/' . $purchase->id).'">Supprimer</a></td>';
        
        return $line;
    }
}