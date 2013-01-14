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
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return Purchase[]
     */
    public function get(DateTime $startDate, DateTime $endDate)
    {
        $p = new Purchase();
        $p->date = 'date';
        $p->id = 1;
        $p->item = 'item';
        $p->payMode = 'pm';
        $p->priceHT = 5;
        $p->priceTTC = 6;
        $p->tax = 19.6;
        $p->offMargin = false;
        
        $pp = clone $p;
        $ppp = clone $p;

        $pp->offMargin = true;
        
        return array($p, $pp, $ppp);
    }
    
    public function create(Purchase $purchase)
    {
        
    }
    
    public function update(Purchase $purchase)
    {
        
    }
    
    public function delete(Purchase $purchase)
    {
        
    }
}