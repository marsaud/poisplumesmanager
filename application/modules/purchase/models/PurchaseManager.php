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
    public function getPeriod(DateTime $startDate, DateTime $endDate)
    {
        $select = $this->_db->select()
                ->from('purchase', Zend_Db_Select::SQL_WILDCARD)
                ->where('date >= ?', $startDate->format('Y-m-d'))
                ->where('date <= ?', $endDate->format('Y-m-d'))
                ->order(array('date ASC', 'item ASC'))
        ;

        $query = $select->query();

        $get = array();

        while ($row = $query->fetchObject())
        {
            $date = new DateTime($row->date);
            
            $purchase = new Purchase();
            $purchase->date = $date->format('Y-m-d');
            $purchase->item = $row->item;
            $purchase->payMode = $row->paymode;
            $purchase->priceHT = $row->priceht;
            $purchase->priceTTC = $row->pricettc;
            $purchase->offMargin = $row->offmargin;
            $purchase->tax = $row->tax;
            $purchase->id = $row->id;

            $get[] = $purchase;
        }

        return $get;
    }

    public function get($id)
    {
        $select = $this->_db->select()
                ->from('purchase', Zend_Db_Select::SQL_WILDCARD)
                ->where('id = ?', $id, Zend_Db::INT_TYPE)
        ;

        $query = $select->query();
        $purchase = null;

        if ($query->rowCount() == 1)
        {
            $row = $query->fetchObject();
            
            $date = new DateTime($row->date);

            $purchase = new Purchase();
            $purchase->date = $date->format('Y-m-d');
            $purchase->item = $row->item;
            $purchase->payMode = $row->paymode;
            $purchase->priceHT = $row->priceht;
            $purchase->priceTTC = $row->pricettc;
            $purchase->offMargin = $row->offmargin;
            $purchase->tax = $row->tax;
            $purchase->id = $row->id;
        }

        return $purchase;
    }

    public function create(Purchase $purchase)
    {
        $bind = array(
            'date' => $purchase->date,
            'item' => $purchase->item,
            'paymode' => $purchase->payMode,
            'priceht' => $purchase->priceHT,
            'pricettc' => $purchase->priceTTC,
            'tax' => $purchase->tax,
            'offmargin' => $purchase->offMargin
        );

        $this->_db->insert('purchase', $bind);
    }

    public function update(Purchase $purchase)
    {
        $bind = array(
            'date' => $purchase->date,
            'item' => $purchase->item,
            'paymode' => $purchase->payMode,
            'priceht' => $purchase->priceHT,
            'pricettc' => $purchase->priceTTC,
            'tax' => $purchase->tax,
            'offmargin' => $purchase->offMargin
        );

        $this->_db->update('purchase', $bind, 'id = ' . $purchase->id);
    }

    public function delete(Purchase $purchase)
    {
        $this->_db->delete('purchase', 'id = ' . $purchase->id);
    }

}