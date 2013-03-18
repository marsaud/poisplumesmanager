<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CashState
 *
 * @author fabrice
 */
class CashState
{
    /**
     *
     * @var float
     */
    public $state;
    
    /**
     *
     * @var string
     */
    public $date;
    
    /**
     *
     * @var string
     */
    public $startDate;
    
    /**
     *
     * @var AbstractMove[]
     */
    public $history = array();
    
     
}