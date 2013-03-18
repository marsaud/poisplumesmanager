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
        $this->view->content = $this->_detailData();
    }

    public function csvAction()
    {

        $this->_helper->getHelper('layout')->disableLayout();
        $this->view->content = $this->_detailData();
    }

    protected function _detailData()
    {
        if (NULL === $this->getRequest()->getParam('startfulldate'))
        {
            $startDate = new DateTime();
        }
        else
        {
            $startDate = new DateTime($this->getRequest()->getParam('startfulldate'));
        }
        
        if (NULL === $this->getRequest()->getParam('endfulldate'))
        {
            $endDate = clone $startDate;
            $endDate->modify('+1 day');
        }
        else
        {
            $endDate = new DateTime($this->getRequest()->getParam('endfulldate'));
        }
        
        $this->view->startfulldate = $startDate->format('Y-m-d');
        $this->view->endfulldate = $endDate->format('Y-m-d');

        /* @var $db Zend_Db_Adapter_Pdo_Abstract */
        $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

        $reportManager = new ReportManager($db);

        $detail = $reportManager->detail($db, $startDate, $endDate);

        return $detail;
    }

    public function weekAction()
    {
        if (empty($_POST))
        {
            $this->view->fulldate = date('Y-m-d');
        }
        else
        {
            
            $date = new Zend_Date($this->getRequest()->getParam('fulldate'));
            $this->view->fulldate = date('Y-m-d', $date->getTimestamp());

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
            $this->view->fulldate = date('Y-m-d');
        }
        else
        {
            $date = new Zend_Date($this->getRequest()->getParam('fulldate'));
            $this->view->fulldate = date('Y-m-d', $date->getTimestamp());
            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                    ->getResource('multidb')
                    ->getDb('ppmdb');

            $reportManager = new ReportManager($db);

            $this->view->report = $reportManager->monthTax($date);
        }
    }

    public function monthCsvAction()
    {   
        $fullDate = $this->getRequest()->getParam('fulldate');
        if (NULL !== $fullDate)
        {
            $reportDate = new Zend_Date($fullDate);
        }
        else
        {
            $reportDate = new Zend_Date();
        }

        $this->view->fulldate = date('Y-m-d', $reportDate->getTimestamp());
        $this->view->year = date('Y', $reportDate->getTimestamp());
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

