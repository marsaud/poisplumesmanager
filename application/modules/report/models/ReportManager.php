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
class ReportManager
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
     * @param Zend_Date $date
     * @param string $part
     * 
     * @return Report
     */
    public function aggregate(Zend_Date $date, $part)
    {
        $select = $this->_db->select()
                ->from(array('c' => 'carttrailer'), array())
                ->joinInner(array('o' => 'operationstrail')
                        , 'c.hash = o.hash'
                        , array(
                            'total' => 'SUM(total_sale_price)',
                            'cb' => 'SUM(cb)',
                            'chq' => 'SUM(chq)',
                            'chr' => 'SUM(chr)',
                            'mon' => 'SUM(mon)'
                            ))
                ->where('payed = ?', true, Zend_Db::PARAM_BOOL);

        switch ($part)
        {
            case self::DAY:
                $select->where('DAY(payment_date) = ?', $date->get(Zend_Date::DAY));
            case self::WEEK:
                if ($date->get(Zend_Date::WEEKDAY_8601) == 7)
                {
                    $week = (string) ($date->get(Zend_Date::WEEK) + 1);
                }
                else
                {
                    $week = $date->get(Zend_Date::WEEK);
                }
                $select->where('WEEK(payment_date) = ?', $week);
            case self::MONTH:
                $select->where('MONTH(payment_date) = ?', $date->get(Zend_Date::MONTH));
            case self::YEAR:
                $select->where('YEAR(payment_date) = ?', $date->get(Zend_Date::YEAR));
                break;
            default:
                break;
        }
        
        $query = $select->query();
        $row = $query->fetch(Zend_Db::FETCH_OBJ);
        $report = new Report();
        $report->cb = $row->cb;
        $report->chq = $row->chq;
        $report->chr = $row->chr;
        $report->mon = $row->mon;
        $report->total = $row->total;
        
        return $report;
    }

}
