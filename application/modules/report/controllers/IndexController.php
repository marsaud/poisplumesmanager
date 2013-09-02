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
            $date = new DateTime();
            $this->view->fulldate = $date->format('Y-m-d');
        }
        else
        {
            
            $date = new DateTime($this->getRequest()->getParam('fulldate'));
            $this->view->fulldate = $date->format('Y-m-d');

            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                    ->getResource('multidb')
                    ->getDb('ppmdb');

            $reportManager = new ReportManager($db);

            $weeklyReport = array();
            $week = new CashFlowReport();

            $zDate = new Zend_Date($date->getTimestamp(), Zend_Date::TIMESTAMP);
            
            for ($weekDay = 1; $weekDay <= 7; $weekDay++)
            {
                $zDate->setWeekday($weekDay);
                $report = $reportManager->aggregate($zDate, ReportManager::DAY, ReportManager::DAY);

                $day = (!empty($report)) ? array_pop($report) : new CashFlowReport();

                $week->add($day);
                $weeklyReport[ucfirst($zDate->get(Zend_Date::WEEKDAY))] = $day;
            }

            $this->view->report = $weeklyReport;
            $this->view->week = $week;
        }
    }

    public function menuAction()
    {
        $this->view->actionName = $this->getFrontController()->getRequest()->getActionName();
    }

    public function monthAction()
    {
        if (empty($_POST))
        {
            $date = new DateTime();
            $this->view->fulldate = $date->format('Y-m-d');
        }
        else
        {
            $date = new DateTime($this->getRequest()->getParam('fulldate'));
            
            $this->view->fulldate = $date->format('Y-m-d');
            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                    ->getResource('multidb')
                    ->getDb('ppmdb');

            $reportManager = new ReportManager($db);

            $zDate = new Zend_Date($date->getTimestamp(), Zend_Date::TIMESTAMP);
            $this->view->report = $reportManager->monthTax($zDate);
        }
    }

    public function monthCsvAction()
    {
        $fullDate = $this->getRequest()->getParam('fulldate');
        if (NULL !== $fullDate)
        {
            $reportDate = new DateTime($fullDate);
        }
        else
        {
            $reportDate = new DateTime();
        }

        $this->view->fulldate = $reportDate->format('Y-m-d');
        
        $zDate = new Zend_Date($reportDate->getTimestamp(), Zend_Date::TIMESTAMP);
        
        $this->view->year = $zDate->get(Zend_Date::YEAR_8601);
        $this->view->month = $zDate->get(Zend_Date::MONTH);
        /* @var $db Zend_Db_Adapter_Pdo_Abstract */
        $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

        $reportManager = new ReportManager($db);

        $this->view->report = $reportManager->monthTax($zDate);
        $this->view->date = new Zend_Date();
        $this->_helper->getHelper('layout')->disableLayout();
    }

    public function cartAction()
    {
        if (empty($_POST))
        {
            $date = new DateTime();
            $this->view->date = $date->format('Y-m-d');
        }
        else
        {
            $date = new DateTime($_POST['date']);
            
            var_dump($date->format(DateTime::RFC1036));
            
            $this->view->date = $date->format('Y-m-d');
            
            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                    ->getResource('multidb')
                    ->getDb('ppmdb');

            $zDate = new Zend_Date($date->getTimestamp(), Zend_Date::TIMESTAMP);
            $reportManager = new ReportManager($db);
            $this->view->report = $reportManager->mediumCart($zDate);
        }
    }

    public function marginAction()
    {
        if (empty($_POST))
        {
            $date = new DateTime();
            $this->view->date = $date->format('Y-m-d');
            $this->view->margin = null;
        }
        else
        {
            $date = new DateTime($_POST['date']);
            
            var_dump($date->format(DateTime::RFC1036));
            
            $this->view->date = $date->format('Y-m-d');
            
            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                    ->getResource('multidb')
                    ->getDb('ppmdb');

            $zDate = new Zend_Date($date->getTimestamp(), Zend_Date::TIMESTAMP);
            $reportManager = new ReportManager($db);
            $this->view->margin = $reportManager->monthMargin($zDate);
        }
    }


}



