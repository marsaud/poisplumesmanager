<?php

class DashBoard_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        require_once APPLICATION_PATH . '/modules/dash-board/models/DashBoardData.php';
    }

    public function indexAction()
    {
        $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');
        
        $dashBoardData = new DashBoardData($db);

        $this->view->turnOverData = $dashBoardData->getTurnover();
        $this->view->cateringData = $dashBoardData->getCatering();
        $this->view->cateringHourData = $dashBoardData->getCateringTiming();
        $this->view->categoryPieData = $dashBoardData->getCategorySales();
        $this->view->turnOverDataLayers = $dashBoardData->extractTurnOverLayers($this->view->turnOverData);
    }

}
