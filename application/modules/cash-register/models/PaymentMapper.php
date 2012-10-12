<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PaymentMapper
 *
 * @author fabrice
 */
class PaymentMapper
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
     * @return Payment[]
     */
    public function get()
    {
        $select = $this->_db->select()
                ->from('payment', array('ref', 'name'));
        $query = $select->query();
        
        $payments = array();
        while($row = $query->fetch(Zend_Db::FETCH_OBJ))
        {
            $payment = new Payment();
            $payment->reference = $row->ref;
            $payment->name = $row->name;
            
            $payments[] = $payment;
        }
        
        return $payments;
    }
}