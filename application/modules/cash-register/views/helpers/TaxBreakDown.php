<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TaxBreakDown
 *
 * @author MAKRIS
 */
class CashRegister_View_Helper_TaxBreakDown extends Zend_View_Helper_Abstract
{

    public function taxBreakDown($totalTax, $totalRawPrice)
    {
        $output = '';
        foreach ($totalTax as $ratio => $value)
        {
            $output .= $ratio . " %\t" . $value . "\t" . $totalRawPrice[$ratio] . "\n";
        }
        $output .= 'Totaux:' . "\t" . array_sum($totalTax) . "\t"
                . array_sum($totalRawPrice);

        return $output;
    }

}
