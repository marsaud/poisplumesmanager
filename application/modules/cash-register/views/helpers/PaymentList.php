<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CashRegister_View_Helper_PaymentList
 *
 * @author fabrice
 */
class CashRegister_View_Helper_PaymentList
{

    /**
     * 
     * @param Payment[] $payments
     */
    public function paymentList(array $payments, $name)
    {
        $paymentList = '';

        foreach ($payments as $value)
        {
            /* @var $value Payment */
            $paymentList .= '<option value="' . $value->reference . '">'
                    . $value->name
                    . '</option>' . PHP_EOL;
        }

        $paymentList = '<select name="' . $name . '">'
                . $paymentList
                . '</select>';
        
        return $paymentList;
    }

}