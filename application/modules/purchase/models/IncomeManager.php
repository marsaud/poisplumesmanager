<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IncomeManager
 *
 * @author fabrice
 */
class IncomeManager
{

    const MAIN_TABLE = 'income';

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

    public function create(Income $income)
    {
        $bind = array(
            'date' => $income->date,
            'item' => $income->item,
            'paymode' => $income->payMode,
            'pricettc' => $income->priceTTC
        );

        $this->_db->insert(self::MAIN_TABLE, $bind);
    }

    public function update(Income $income)
    {
        $bind = array(
            'date' => $income->date,
            'item' => $income->item,
            'paymode' => $income->payMode,
            'pricettc' => $income->priceTTC
        );

        $this->_db->update(self::MAIN_TABLE, $bind, 'id = ' . $income->id);
    }

    public function delete(Income $income)
    {
        $this->_db->delete(self::MAIN_TABLE, 'id = ' . $income->id);
    }

    /**
     * 
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return Income[]
     */
    public function getPeriod(DateTime $startDate, DateTime $endDate)
    {
        $select = $this->_db->select()
                ->from(self::MAIN_TABLE, Zend_Db_Select::SQL_WILDCARD)
                ->where('date >= ?', $startDate->format('Y-m-d'))
                ->where('date <= ?', $endDate->format('Y-m-d'))
                ->order(array('date ASC', 'item ASC'))
        ;

        $query = $select->query();

        $get = array();

        while ($row = $query->fetchObject())
        {
            $date = new DateTime($row->date);

            $income = new Income();
            $income->date = $date->format('Y-m-d');
            $income->item = $row->item;
            $income->payMode = $row->paymode;
            $income->priceTTC = $row->pricettc;
            $income->id = $row->id;

            $get[] = $income;
        }

        return $get;
    }

    public function get($id)
    {
        $select = $this->_db->select()
                ->from(self::MAIN_TABLE, Zend_Db_Select::SQL_WILDCARD)
                ->where('id = ?', $id, Zend_Db::INT_TYPE)
        ;

        $query = $select->query();
        $income = null;

        if ($query->rowCount() == 1)
        {
            $row = $query->fetchObject();

            $date = new DateTime($row->date);

            $income = new Income();
            $income->date = $date->format('Y-m-d');
            $income->item = $row->item;
            $income->payMode = $row->paymode;
            $income->priceTTC = $row->pricettc;
            $income->id = $row->id;
        }

        return $income;
    }

}