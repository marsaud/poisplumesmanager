<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Report
 *
 * @author MAKRIS
 */
class Report
{

    const DAY = 'day';
    const WEEK = 'week';
    const MONTH = 'month';
    const YEAR = 'year';

    /**
     *
     * @var Zend_Db_Adapter_Pdo_Abstract
     */
    protected $_db;

    public function __construct(Zend_Db_Adapter_Pdo_Abstract $db)
    {
        $this->_db = $db;
    }

    public function aggregate(Zend_Date $date, $part)
    {
        $select = $this->_db->select()
                ->from(array('c' => 'carttrailer'), array())
                ->joinInner(array('o' => 'operationstrail')
                        , 'c.hash = o.hash'
                        , array('total' => 'SUM(total_sale_price)'))
                ->where('payed = ?', true, Zend_Db::PARAM_BOOL);

        switch ($part)
        {
            case self::DAY:
                $select->where('DAY(payment_date) = ?', $date->get(Zend_Date::DAY));
            case self::WEEK:
                $select->where('WEEK(payment_date) = ?', $date->get(Zend_Date::WEEK));
            case self::MONTH:
                $select->where('MONTH(payment_date) = ?', $date->get(Zend_Date::MONTH));
            case self::YEAR:
                $select->where('YEAR(payment_date) = ?', $date->get(Zend_Date::YEAR));
                break;
            default:
                break;
        }
        
        $query = $select->query();
        $total = (float) $query->fetchColumn();
        
        return $total;
    }

}
