<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DashBoardData
 *
 * @author fabrice
 */
class DashBoardData
{

    protected $_monthNames = array(
        1 => 'janvier',
        2 => 'février',
        3 => 'mars',
        4 => 'avril',
        5 => 'mai',
        6 => 'juin',
        7 => 'juillet',
        8 => 'août',
        9 => 'septembre',
        10 => 'octobre',
        11 => 'novembre',
        12 => 'décembre'
    );
    protected $_dayNames = array(
        0 => 'lundi',
        1 => 'mardi',
        2 => 'mercredi',
        3 => 'jeudi',
        4 => 'vendredi',
        5 => 'samedi',
        6 => 'dimanche'
    );

    /**
     *
     * @var Zend_Db_Adapter_Pdo_Abstract
     */
    protected $_db;

    public function __construct(Zend_Db_Adapter_Pdo_Abstract $db)
    {
        $this->_db = $db;
    }

    public function getTurnover()
    {
        $select = $this->_db->select()
                ->from(array('ol' => 'operationlines'), array(
                    'ht' => new Zend_Db_Expr('SUM(raw_price * quantity)')
                ))
                ->joinInner(array('ct' => 'carttrailer'), 'ct.hash = ol.hash', array(
                    'year' => new Zend_Db_Expr('YEAR(ct.payment_date)'),
                    'month' => new Zend_Db_Expr('MONTH(ct.payment_date)')
                ))
                ->where('ct.payed = ?', 1, Zend_Db::INT_TYPE)
                ->group(array(new Zend_Db_Expr('YEAR(ct.payment_date)'), new Zend_Db_Expr('MONTH(ct.payment_date)')));

        $query = $select->query();

        $data = array();
        for ($month = 1; $month <= 12; $month++)
        {
            $data[$month] = new stdClass();
            $data[$month]->month = $this->_monthNames[$month];
        }

        while (false !== ($row = $query->fetchObject()))
        {
            $data[$row->month]->{$row->year} = $row->ht;
        }

        ksort($data);
        $finalData = array_values($data);

        return $finalData;
    }

    public function getCatering()
    {
        "select 
WEEKDAY(ct.payment_date) as `day`, 
(HOUR(ct.payment_date) < 15) as `afternoon`, 
ca.category_ref as `category`, 
SUM(ol.quantity * ol.raw_price) as `income`
from operationlines AS ol
inner join categoryarticle AS ca ON ca.article_ref = ol.reference
inner join carttrailer AS ct on ct.`hash` = ol.`hash`
where ca.category_ref in (\"CafÃ© - boisson\", \"resto\")
and ct.payed = 1
group by DAYNAME(ct.payment_date), (HOUR(ct.payment_date) >= 15), ca.category_ref
order by day, afternoon, category";

        $select = $this->_db->select()
                ->from(
                        array('ol' => 'operationlines')
                        , array('income' => new Zend_Db_Expr('SUM(ol.quantity * ol.raw_price)'))
                )
                ->joinInner(
                        array('ca' => 'categoryarticle')
                        , 'ca.article_ref = ol.reference'
                        , array('category' => 'ca.category_ref')
                )
                ->joinInner(
                        array('ct' => 'carttrailer')
                        , 'ct.hash = ol.hash'
                        , array(
                    'day' => new Zend_Db_Expr('WEEKDAY(ct.payment_date)'),
                    'afternoon' => new Zend_Db_Expr('(HOUR(ct.payment_date) >= 15)')
                        )
                )
                ->where('ct.payed = ?', 1, Zend_Db::INT_TYPE)
                ->where('ca.category_ref in (?)', array('Café - boisson', 'resto'))
                ->group(array(
                    new Zend_Db_Expr('DAYNAME(ct.payment_date)'),
                    new Zend_Db_Expr('(HOUR(ct.payment_date) < 15)'),
                    new Zend_Db_Expr('ca.category_ref')
                ))
                ->order(array('day', 'afternoon', 'category'))
        ;

        $query = $select->query();

        $data = array();
        for ($day = 0; $day <= 6; $day++)
        {
            $data[$day] = new stdClass();
            $data[$day]->day = $this->_dayNames[$day];
        }

        while (false !== ($row = $query->fetchObject()))
        {
            $fieldName = '';
            if ('Café - boisson' == $row->category)
            {
                $fieldName .= 'drink_';
            }
            elseif ('resto' == $row->category)
            {
                $fieldName .= 'food_';
            }

            if ($row->afternoon)
            {
                $fieldName .= 'snack';
            }
            else
            {
                $fieldName .= 'lunch';
            }

            $data[$row->day]->$fieldName = $row->income;
        }

        ksort($data);
        $finalData = array_values($data);

        return $finalData;
    }

    public function getCateringTiming()
    {
        "select 
HOUR(ct.payment_date) as \"hour\", ca.category_ref as \"category\", count(ol.reference) as \"sales\"
from operationlines AS ol
inner join categoryarticle AS ca ON ca.article_ref = ol.reference
inner join carttrailer AS ct on ct.`hash` = ol.`hash`
where ca.category_ref in (\"CafÃ© - boisson\", \"resto\")
and ct.payed = 1
group by ca.category_ref, HOUR(ct.payment_date)
order by HOUR(ct.payment_date), ca.category_ref";

        $select = $this->_db->select()
                ->from(
                        array('ol' => 'operationlines')
                        , array('sales' => new Zend_Db_Expr('count(ol.reference)'))
                )
                ->joinInner(
                        array('ca' => 'categoryarticle')
                        , 'ca.article_ref = ol.reference'
                        , array('category' => 'ca.category_ref')
                )
                ->joinInner(
                        array('ct' => 'carttrailer')
                        , 'ct.hash = ol.hash'
                        , array(
                    'hour' => new Zend_Db_Expr('HOUR(ct.payment_date)')
                        )
                )
                ->where('ct.payed = ?', 1, Zend_Db::INT_TYPE)
                ->where('ca.category_ref in (?)', array('Café - boisson', 'resto'))
                ->group(array(
                    new Zend_Db_Expr('HOUR(ct.payment_date)'),
                    new Zend_Db_Expr('ca.category_ref')
                ))
                ->order(array('hour', 'category'))
        ;

        $query = $select->query();

        $data = array();

        for ($hour = 0; $hour <= 23; $hour++)
        {
            $data[$hour] = new stdClass();
            $data[$hour]->hour = $hour;
        }

        while (false !== ($row = $query->fetchObject()))
        {
            $data[$row->hour]->{$row->category} = $row->sales;
        }

        ksort($data);
        $finalData = array_values($data);

        return $finalData;
    }

    public function getCategorySales()
    {
        $select = $this->_db->select()
                ->from(
                        array('ol' => 'operationlines')
                        , array('sales' => new Zend_Db_Expr('SUM(ol.quantity * ol.raw_price)'))
                )
                ->joinInner(
                        array('ca' => 'categoryarticle')
                        , 'ca.article_ref = ol.reference'
                        , array()
                )
                ->joinInner(array('c' => 'category'), 'c.ref = ca.category_ref', array('name'))
                ->joinInner(
                        array('ct' => 'carttrailer')
                        , 'ct.hash = ol.hash'
                        , array()
                )
                ->where('ct.payed = ?', 1, Zend_Db::INT_TYPE)
                ->group('ca.category_ref')
                ->order('sales DESC');

        $query = $select->query();
        $data = $query->fetchAll(Zend_Db::FETCH_OBJ);

        return $data;
    }

    public function extractTurnOverLayers($turnOver)
    {
        $years = array();
        foreach ($turnOver as $object)
        {
            $years = array_merge($years, array_keys(get_object_vars($object)));
        }
        $layers = array_unique($years);
        $finalLayers = array();
        foreach ($layers as $value)
        {
            if ('month' != $value)
            {
                $finalLayers[] = (string) $value;
            }
        }
        sort($finalLayers);
        return $finalLayers;
    }

}
