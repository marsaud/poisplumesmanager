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
        // return '<pre>' . __METHOD__ . PHP_EOL . print_r($cashState, true) . '</pre>';
        
        $output = '';
        
        foreach ($cashState->history as $move)
        {
            $f = self::$_renderers[get_class($move)];
            $output .= $this->$f($move);
        }
        
        return '<table>' . $output . '</table>';
    }
    
    /**
     * 
     * @param Purchase $purchase
     * @return string
     */
    protected function _renderPurchase(Purchase $purchase)
    {
        return '<tr><td>'. print_r($purchase, true) . '</td></tr>';
    }
    
    /**
     * 
     * @param CashMove $cashMove
     * @return string
     */
    protected function _renderCashMove(CashMove $cashMove)
    {
        return '<tr><td>'. print_r($cashMove, true) . '</td></tr>';
    }

}