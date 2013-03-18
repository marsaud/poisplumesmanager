<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CashMove
 *
 * @author fabrice
 */
class CashMove extends AbstractMove
{
    public function __construct()
    {
        $this->payMode = 'mon';
    }

}