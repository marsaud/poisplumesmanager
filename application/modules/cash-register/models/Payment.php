<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment
 *
 * @author fabrice
 */
class Payment
{

    /**
     *
     * @var string
     */
    public $reference;
    /**
     *
     * @var string
     */
    public $name;
    /**
     *
     * @var float
     */
    public $percieved;
    /**
     *
     * @var float
     */
    public $returned;
    
    /**
     * 
     * @param Payment[] $payments
     * 
     * @return float
     */
    public static function consolidate(array $payments)
    {
        $percieved = 0;
        
        foreach ($payments as $payment)
        {
            /* @var $payment Payment */
            $percieved += $payment->percieved;
            $percieved -= $payment->returned;
        }
        
        return $percieved;
    }

}