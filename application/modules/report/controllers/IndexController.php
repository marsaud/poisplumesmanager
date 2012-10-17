<?php

class Report_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        /* @var $db Zend_Db_Adapter_Pdo_Abstract */
        $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

        $select = $db->select()
                ->from('carttrailer', array('hash', 'payment_date'))
                ->where('payed = ?', true, Zend_Db::PARAM_BOOL)
                ->order('payment_date DESC')
        ;
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

        $this->view->content = $csv;
    }

    public function csvAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();

        /* @var $db Zend_Db_Adapter_Pdo_Abstract */
        $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

        $select = $db->select()
                ->from('carttrailer', array('hash', 'payment_date'))
                ->where('payed = ?', true, Zend_Db::PARAM_BOOL)
                ->order('payment_date DESC')
        ;
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

        $this->view->content = $csv;
    }

}

