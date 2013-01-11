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
     * @return CashFlowReport
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
            $report = new CashFlowReport();
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
        /**
         * SELECT 
         * sum(total) as "Total",
         * count(total) as "Nombre de paniers",
         * sum(total)/count(total) as "Panier moyen" 
         * FROM `reporttrailview`
         * WHERE month(pay_date) = 12;
         */
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

    /**
     * @todo On a la BDD en privé...
     * 
     * @param Zend_Db_Adapter_Pdo_Abstract $db
     * @param Zend_Date $startDate
     * @param Zend_Date $endDate
     * @return string
     */
    public function detail(Zend_Db_Adapter_Pdo_Abstract $db, Zend_Date $startDate = NULL, Zend_Date $endDate = NULL)
    {
        $select = $db->select()
                ->from('carttrailer', array('hash', 'payment_date'))
                ->where('payed = ?', true, Zend_Db::PARAM_BOOL)
                ->order('payment_date DESC')
        ;

        if (NULL !== $startDate)
        {
            $startDate->setHour(0);
            $startDate->setMinute(0);
            $startDate->setSecond(0);
            $select->where('payment_date >= ?', $startDate->getIso());
        }

        if (NULL !== $endDate)
        {
            $endDate->setHour(23);
            $endDate->setMinute(59);
            $endDate->setSecond(59);
            $select->where('payment_date <= ?', $endDate->getIso());
        }

        $query = $select->query();
        $carts = $query->fetchAll(Zend_Db::FETCH_OBJ);

        $csv = implode(';', array(
                    'Référence',
                    'Prix',
                    'Quantité',
                    'Sous-total',
                    'CB',
                    'Chèque',
                    'CH Resto',
                    'Espèces'
                )) . PHP_EOL;

        foreach ($carts as $cart)
        {
            $selectOperation = $db->select()
                    ->from(array('ot' => 'operationstrail'), array('total_sale_price', 'cb', 'chq', 'chr', 'mon'))
                    ->joinInner(array('ol' => 'operationlines'), 'ot.hash = ol.hash', array('reference', 'sale_price', 'quantity'))
                    ->where('ot.hash = ?', $cart->hash);

            $queryOperation = $selectOperation->query();

            $row = $queryOperation->fetch(Zend_Db::FETCH_OBJ);

            $csvBuffer = implode(';', array(
                        'Date : ',
                        $cart->payment_date,
                        'TOTAL : ',
                        $row->total_sale_price,
                        $row->cb,
                        $row->chq,
                        $row->chr,
                        $row->mon
                    ))
                    . PHP_EOL;

            $csvLines = implode(';', array(
                        $row->reference,
                        $row->sale_price,
                        $row->quantity,
                        $row->sale_price * $row->quantity
                    ))
                    . PHP_EOL;

            while ($row = $queryOperation->fetch(Zend_Db::FETCH_OBJ))
            {
                $csvLines .= implode(';', array(
                            $row->reference,
                            $row->sale_price,
                            $row->quantity,
                            $row->sale_price * $row->quantity
                        ))
                        . PHP_EOL;
            }

            $csv .= $csvLines . $csvBuffer . PHP_EOL;
        }

        return $csv;
    }

}
