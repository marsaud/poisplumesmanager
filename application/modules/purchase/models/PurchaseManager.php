<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PurchaseManager
 *
 * @author fabrice
 */
class PurchaseManager
{

    /**
     *
     * @var Zend_Db_Adapter_Pdo_Abstract
     */
    protected $_db;

    /**
     * 
     * @param Zend_Db_Adapter_Pdo_Abstract $db
     */
    public function __construct(Zend_Db_Adapter_Pdo_Abstract $db)
    {
        $this->_db = $db;
    }

    /**
     * 
     * @param Zend_Date $startDate
     * @param Zend_Date $endDate
     * @return Purchase[]
     */
    public function get(Zend_Date $startDate, Zend_Date $endDate)
    {
        $p = new Purchase();
        $p->date = 'date';
        $p->id = 1;
        $p->item = 'item';
        $p->payMode = 'pm';
        $p->priceHT = 'HT';
        $p->priceTTC = 'TTC';
        $p->tax = 'tax';
        
        return array($p, $p, $p);
    }
}