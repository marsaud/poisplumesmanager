<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CashStateDisplay
 *
 * @author fabrice
 */
class Purchase_View_Helper_CashStateDisplay extends Zend_View_Helper_Abstract
{

    protected static $_renderers = array(
        'CashMove' => '_renderCashMove',
        'Purchase' => '_renderPurchase'
    );

    public function cashStateDisplay(CashState $cashState)
    {
        $output = '';

        foreach ($cashState->history as $move)
        {
            $f = self::$_renderers[get_class($move)];
            $output .= $this->$f($move);
        }

        return '<table><tr><th>Objet</th><th>HT</th><th>TVA</th><th>TTC</th><th>Date</th><th>Actions</th></tr>' . $output . '</table>';
    }

    /**
     * 
     * @param Purchase $purchase
     * @return string
     */
    protected function _renderPurchase(Purchase $purchase)
    {
        $line = '';

        $line .= '<td>' . $purchase->item . '</td>';
        $line .= '<td> -' . $this->view->currency($purchase->priceHT) . '</td>';
        $line .= '<td>' . $purchase->tax . ' %</td>';
        $line .= '<td> -' . $this->view->currency($purchase->priceTTC) . '</td>';
//        $line .= '<td>' . $purchase->payMode . '</td>';
//        $line .= '<td>' . ($purchase->offMargin ? 'X' : '') . '</td>';
        $line .= '<td>' . $purchase->date . '</td>';
//        $line .= '<td><a href="'.$this->view->baseUrl('purchase/index/update/purchaseid/' . $purchase->id).'">Modifier</a>';
//        $line .= '&nbsp;<a href="'.$this->view->baseUrl('purchase/manage/delete/purchaseid/' . $purchase->id).'" class="confirm">Supprimer</a></td>';
        $line .= '<td></td>';
        

        return '<tr class="readonlyinfo">' . $line . '</tr>';
    }

    /**
     * 
     * @param CashMove $cashMove
     * @return string
     */
    protected function _renderCashMove(CashMove $cashMove)
    {
        $line = '';

        $line .= '<td>' . $cashMove->item . '</td>';
        $line .= '<td></td>';
        $line .= '<td></td>';
        $line .= '<td>' . $this->view->currency($cashMove->priceTTC) . '</td>';
//        $line .= '<td>' . $purchase->payMode . '</td>';
//        $line .= '<td>' . ($purchase->offMargin ? 'X' : '') . '</td>';
        $line .= '<td>' . $cashMove->date . '</td>';
        $line .= '<td><a href="'.$this->view->baseUrl('purchase/cash/update-screen/cashmoveid/' . $cashMove->id).'">Modifier</a>';
        $line .= '&nbsp;<a href="'.$this->view->baseUrl('purchase/cash/delete/cashmoveid/' . $cashMove->id).'" class="confirm">Supprimer</a></td>';
        
        return '<tr>' . $line . '</tr>';
    }

}