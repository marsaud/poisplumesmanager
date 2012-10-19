<?php

class Report_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        require_once APPLICATION_PATH . '/modules/report/models/Report.php';
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

            $csv .= $csvLines . $csvBuffer;
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

    public function aggregateAction()
    {
        $this->view->year = date('Y');
        $this->view->month = date('m');
        $this->view->day = date('d');

        if (!empty($_POST))
        {
            $this->view->year = $_POST['year'];
            $this->view->month = $_POST['month'];
            $this->view->day = $_POST['day'];
            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                    ->getResource('multidb')
                    ->getDb('ppmdb');

            $date = new Zend_Date(implode('-', array(
                                $_POST['year'],
                                $_POST['month'],
                                $_POST['day']
                            )));

            $report = new Report($db);

            $weeklyReport = array();
            for ($weekDay = 1; $weekDay <= 7; $weekDay++)
            {
                $date->setWeekday($weekDay);
                $dateForString = new DateTime($date->getIso());
                $weeklyReport[$dateForString->format('l')] =
                        $this->view->currency(
                        $report->aggregate($date, Report::DAY)
                );
            }

            $this->view->report = $weeklyReport;
        }
    }

    public function menuAction()
    {
        
    }

}

