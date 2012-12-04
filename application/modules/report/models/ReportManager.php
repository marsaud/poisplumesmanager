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
    public function aggregate(Zend_Date $date, $key, $period)
    {
        $columns = array(
            'total' => 'SUM(total)',
            'cb' => 'SUM(cb)',
            'chq' => 'SUM(chq)',
            'chr' => 'SUM(chr)',
            'mon' => 'SUM(mon)'
        );


        switch ($key)
        {
            case self::DAY:
                $columns['part'] = 'DAY(pay_date)';
                break;
            case self::WEEK:
                $columns['part'] = 'WEEK(pay_date, 3)';
                break;
            case self::MONTH:
                $columns['part'] = 'MONTH(pay_date)';
                break;
            case self::YEAR:
                $columns['part'] = 'YEAR(pay_date)';
                break;
            default:
                throw new RuntimeException('Unhandled report date part');
                break;
        }

        $select = $this->_db->select()
                ->from(array('v' => 'reporttrailview'), $columns);

        switch ($period)
        {
            case self::DAY:
                $select->where('DAY(pay_date) = ?', $date->get(Zend_Date::DAY));
            case self::WEEK:
                $select->where('WEEK(pay_date, 3) = ?', $date->get(Zend_Date::WEEK));
            case self::MONTH:
                $select->where('MONTH(pay_date) = ?', $date->get(Zend_Date::MONTH));
            case self::YEAR:
                $select->where('YEAR(pay_date) = ?', $date->get(Zend_Date::YEAR));
                break;
            default:
                throw new RuntimeException('Unhandled report date part');
                break;
        }

        $select->group('part')
                ->order(array('part ASC'));

        $aggregate = array();

        $query = $select->query();
        while ($row = $query->fetch(Zend_Db::FETCH_OBJ))
        {
            $report = new Report();
            $report->cb = $row->cb;
            $report->chq = $row->chq;
            $report->chr = $row->chr;
            $report->mon = $row->mon;
            $report->total = $row->total;
            $aggregate[$row->part] = $report;
        }

        $row = $query->fetch(Zend_Db::FETCH_OBJ);

        return $aggregate;
    }

    public function monthTax(Zend_Date $date)
    {
        "SELECT ol.tax_ratio, sum(final_price * quantity)
FROM `operationlines` ol
INNER JOIN `carttrailer` ct on ct.hash = ol.hash
WHERE MONTH(ct.payment_date) = 11 AND YEAR(ct.payment_date) = 2012
GROUP BY ol.tax_ratio";
        
        $select = $this->_db->select()
                ->from(array('ol' => 'operationlines'), array('Taux' => 'tax_ratio', 'Total' => new Zend_Db_Expr('SUM(final_price * quantity)')))
            ->joinInner(array('ct' => 'carttrailer'), 'ct.hash = ol.hash', array())
            ->where('MONTH(ct.payment_date) = ?', $date->get(Zend_Date::MONTH))
            ->where('YEAR(ct.payment_date) = ?', $date->get(Zend_Date::YEAR))
            ->group('ol.tax_ratio');
        
        $query = $select->query();
        $output = $query->fetchAll();
        
        return $output;
    }
    
    public function mediumCart(Zend_Date $date)
    {
        "SELECT sum(total), count(total), sum(total)/count(total) as mean FROM `reporttrailview` 
WHERE month(pay_date) = 12";
        
        $select = $this->_db->select()
                ->from('reporttrailview', array(
                    'Total' => new Zend_Db_Expr('SUM(total)'),
                    'Nombre de paniers' => new Zend_Db_Expr('COUNT(total)'),
                    'Panier moyen' => new Zend_Db_Expr('SUM(total)/COUNT(total)')
                ))
            ->where('MONTH(pay_date) = ?', $date->get(Zend_Date::MONTH))
            ->where('YEAR(pay_date) = ?', $date->get(Zend_Date::YEAR));
        
        $query = $select->query();
        $output = $query->fetchAll();
        
        return $output;
    }

}