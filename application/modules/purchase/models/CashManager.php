<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CashManager
 *
 * @author fabrice
 */
class CashManager
{
    public function insert(CashMove $cashMove)
    {
        
    }
    
    public function delete(CashMove $cashMove)
    {
        
    }
    
    public function update(CashMove $cashMove)
    {
        
    }
    
    /**
     * 
     * @param string $date
     * @param string $startDate
     * 
     * @return CashState
     */
    public function getState($date, $startDate = NULL)
    {
        $state = new CashState();
        
        $a = new CashMove();
        $a->date = '2013-01-01';
        $a->item = 'First';
        $a->priceTTC = '10';
        
        $b = new CashMove();
        $b->date = '2013-01-05';
        $b->item = 'Second';
        $b->priceTTC = '20';
        
        $c = new Purchase();
        $c->date = '2013-01-03';
        $c->item = 'Out1';
        $c->payMode = 'mon';
        $c->priceHT = 4.3;
        $c->priceTTC = 4.5;
        $c->tax = 10;
        
        $d = new Purchase();
        $d->date = '2013-01-04';
        $d->item = 'Out1';
        $d->payMode = 'mon';
        $d->priceHT = 1.3;
        $d->priceTTC = 1.5;
        $d->tax = 10;
        
        $state->history[$a->date] = $a;
        $state->history[$b->date] = $b;
        $state->history[$c->date] = $c;
        $state->history[$d->date] = $d;
        
        $arr = $state->history;
        ksort($arr);
        $state->history = $arr;
        
        return $state;
    }
}