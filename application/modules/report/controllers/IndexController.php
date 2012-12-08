<?php

class Report_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        require_once APPLICATION_PATH . '/modules/report/models/Report.php';
        require_once APPLICATION_PATH . '/modules/report/models/ReportManager.php';
    }

    public function indexAction()
    {
        
    }

    public function detailAction()
    {
        $this->_detailData();
    }

    public function csvAction()
    {
        
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_detailData();
    }
    
    protected function _detailData()
    {
        if (empty($_POST))
        {
            $this->view->startyear = $this->view->endyear = date('Y');
            $this->view->startmonth = $this->view->endmonth = date('m');
            $this->view->startday = date('d');
            $this->view->endday = date('d', time() + 60 * 60 * 24);
            $this->view->startfulldate = date('Y-m-d');
            $this->view->endfulldate = date('Y-m-d', time() + 60 * 60 * 24);
        }
        else
        {
            if (isset($_POST['startfulldate']))
            {
                $startDate = new Zend_Date($_POST['startfulldate']);
                $this->view->startfulldate = $_POST['startfulldate'];
                $startFullDate = explode('-', $_POST['startfulldate']);
                $this->view->startyear = $startFullDate[0];
                $this->view->startmonth = $startFullDate[1];
                $this->view->startday = $startFullDate[2];
                
                $endDate = new Zend_Date($_POST['endfulldate']);
                $this->view->endfulldate = $_POST['endfulldate'];
                $endFullDate = explode('-', $_POST['endfulldate']);
                $this->view->endyear = $endFullDate[0];
                $this->view->endmonth = $endFullDate[1];
                $this->view->endday = $endFullDate[2];
            }
            else
            {
                $this->view->startfulldate = implode('-', array(
                    $_POST['startyear'],
                    $_POST['startmonth'],
                    $_POST['startday']
                        ));
                $this->view->endfulldate = implode('-', array(
                    $_POST['endyear'],
                    $_POST['endmonth'],
                    $_POST['endday']
                        ));
                
                $this->view->startyear = $_POST['startyear'];
                $this->view->startmonth = $_POST['startmonth'];
                $this->view->startday = $_POST['startday'];
                $startDate = new Zend_Date(implode('-', array(
                                    $_POST['startyear'],
                                    $_POST['startmonth'],
                                    $_POST['startday']
                                )));
                $this->view->endyear = $_POST['endyear'];
                $this->view->endmonth = $_POST['endmonth'];
                $this->view->endday = $_POST['endday'];
                $endDate = new Zend_Date(implode('-', array(
                                    $_POST['endyear'],
                                    $_POST['endmonth'],
                                    $_POST['endday']
                                )));
            }
        }
        
        /* @var $db Zend_Db_Adapter_Pdo_Abstract */
        $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

        $reportManager = new ReportManager($db);

        $this->view->content = $reportManager->detail($db, $startDate, $endDate);
    }

//    protected function _getDetailedTable(Zend_Db_Adapter_Pdo_Abstract $db)
//    {
//        $select = $db->select()
//                ->from('carttrailer', array('hash', 'payment_date'))
//                ->where('payed = ?', true, Zend_Db::PARAM_BOOL)
//                ->order('payment_date DESC')
//        ;
//        $query = $select->query();
//        $carts = $query->fetchAll(Zend_Db::FETCH_OBJ);
//
//        $csv = implode(';', array(
//                    'Référence',
//                    'Prix',
//                    'Quantité',
//                    'Sous-total',
//                    'CB',
//                    'Chèque',
//                    'CH Resto',
//                    'Espèces'
//                )) . PHP_EOL;
//
//        foreach ($carts as $cart)
//        {
//            $selectOperation = $db->select()
//                    ->from(array('ot' => 'operationstrail'), array('total_sale_price', 'cb', 'chq', 'chr', 'mon'))
//                    ->joinInner(array('ol' => 'operationlines'), 'ot.hash = ol.hash', array('reference', 'sale_price', 'quantity'))
//                    ->where('ot.hash = ?', $cart->hash);
//
//            $queryOperation = $selectOperation->query();
//
//            $row = $queryOperation->fetch(Zend_Db::FETCH_OBJ);
//
//            $csvBuffer = implode(';', array(
//                        'Date : ',
//                        $cart->payment_date,
//                        'TOTAL : ',
//                        $row->total_sale_price,
//                        $row->cb,
//                        $row->chq,
//                        $row->chr,
//                        $row->mon
//                    ))
//                    . PHP_EOL;
//
//            $csvLines = implode(';', array(
//                        $row->reference,
//                        $row->sale_price,
//                        $row->quantity,
//                        $row->sale_price * $row->quantity
//                    ))
//                    . PHP_EOL;
//
//            while ($row = $queryOperation->fetch(Zend_Db::FETCH_OBJ))
//            {
//                $csvLines .= implode(';', array(
//                            $row->reference,
//                            $row->sale_price,
//                            $row->quantity,
//                            $row->sale_price * $row->quantity
//                        ))
//                        . PHP_EOL;
//            }
//
//            $csv .= $csvLines . $csvBuffer . PHP_EOL;
//        }
//
//        return $csv;
//    }

    public function weekAction()
    {
        if (empty($_POST))
        {
            $this->view->year = date('Y');
            $this->view->month = date('m');
            $this->view->day = date('d');
            $this->view->fulldate = date('Y-m-d');
        }
        else
        {
            if (isset($_POST['fulldate']))
            {
                $date = new Zend_Date($_POST['fulldate']);
                $this->view->fulldate = $_POST['fulldate'];
                $fullDate = explode('-', $_POST['fulldate']);
                $this->view->year = $fullDate[0];
                $this->view->month = $fullDate[1];
                $this->view->day = $fullDate[2];
            }
            else
            {
                $this->view->fulldate = implode('-', array(
                    $_POST['year'],
                    $_POST['month'],
                    $_POST['day']
                        ));
                $this->view->year = $_POST['year'];
                $this->view->month = $_POST['month'];
                $this->view->day = $_POST['day'];
                $date = new Zend_Date(implode('-', array(
                                    $_POST['year'],
                                    $_POST['month'],
                                    $_POST['day']
                                )));
            }

            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                    ->getResource('multidb')
                    ->getDb('ppmdb');

            $reportManager = new ReportManager($db);

            $weeklyReport = array();
            $week = new CashFlowReport();

            for ($weekDay = 1; $weekDay <= 7; $weekDay++)
            {
                $date->setWeekday($weekDay);
                $report = $reportManager->aggregate($date, ReportManager::DAY, ReportManager::DAY);

                $day = (!empty($report)) ? array_pop($report) : new CashFlowReport();

                $week->add($day);
                $weeklyReport[ucfirst($date->get(Zend_Date::WEEKDAY))] = $day;
            }

            $this->view->report = $weeklyReport;
            $this->view->week = $week;
        }
    }

    public function menuAction()
    {
        
    }

    public function monthAction()
    {
        if (empty($_POST))
        {
            $this->view->year = date('Y');
            $this->view->month = date('m');
        }
        else
        {
            $this->view->year = $_POST['year'];
            $this->view->month = $_POST['month'];
            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                    ->getResource('multidb')
                    ->getDb('ppmdb');

            $date = new Zend_Date(implode('-', array(
                                $_POST['year'],
                                $_POST['month'],
                                '01'
                            )));

            $reportManager = new ReportManager($db);

            $this->view->report = $reportManager->monthTax($date);
        }
    }

    public function monthCsvAction()
    {
        isset($_GET['month'])
                || $_GET['month'] = date('m');

        isset($_GET['year'])
                || $_GET['year'] = date('Y');

        $reportDate = new Zend_Date(implode('-', array(
                            $_GET['year'],
                            $_GET['month'],
                            '01'
                        )));

        $this->view->year = $_GET['year'];
        $this->view->month = $reportDate->get(Zend_Date::MONTH_NAME);
        /* @var $db Zend_Db_Adapter_Pdo_Abstract */
        $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

        $reportManager = new ReportManager($db);

        $this->view->report = $reportManager->monthTax($reportDate);
        $this->view->date = new Zend_Date();
        $this->_helper->getHelper('layout')->disableLayout();
    }

    public function cartAction()
    {
        if (empty($_POST))
        {
            $this->view->year = date('Y');
            $this->view->month = date('m');
        }
        else
        {
            $this->view->year = $_POST['year'];
            $this->view->month = $_POST['month'];
            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                    ->getResource('multidb')
                    ->getDb('ppmdb');

            $date = new Zend_Date(implode('-', array(
                                $_POST['year'],
                                $_POST['month'],
                                '01'
                            )));

            $reportManager = new ReportManager($db);

            $this->view->report = $reportManager->mediumCart($date);
        }
    }

}

