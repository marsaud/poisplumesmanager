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
class Purchase_View_Helper_IncomeDisplay extends Zend_View_Helper_Abstract
{
    /**
     * 
     * @param Income[] $incomes
     * 
     * @return string
     */
    public function incomeDisplay(array $incomes)
    {
        $table = '';
        
        foreach($incomes as $income)
        {
            $table .= '<tr class="incomeline">' . $this->_displayIncomeLine($income) . '</tr>';
        }
        
        $table = '<table><tr><th>Objet</th><th>Montant(Total TTC)</th><th>Paiement</th><th>Date</th><th>Actions</th></tr>' . $table . '</table>';
        
        return $table;
    }
    
    protected function _displayIncomeLine(Income $income)
    {
        $line = '';
        
        $line .= '<td>' . $income->item . '</td>';
        $line .= '<td>' . $this->view->currency($income->priceTTC) . '</td>';
        $line .= '<td>' . $income->payMode . '</td>';
        $line .= '<td>'. $income->date .'</td>';
        $line .= '<td><a href="'.$this->view->baseUrl('purchase/income/update-screen/incomeid/' . $income->id).'">Modifier</a>';
        $line .= '&nbsp;<a href="'.$this->view->baseUrl('purchase/income/delete/incomeid/' . $income->id).'" class="confirm">Supprimer</a></td>';
        
        return $line;
    }
}