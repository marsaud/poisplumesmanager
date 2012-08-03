<?php

class Admin_TaxController extends Zend_Controller_Action
{

    public function init()
    {
        require_once APPLICATION_PATH . '/models/Tax.php';
        require_once APPLICATION_PATH . '/models/TaxMapper.php';

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        /* @var $ajaxContext Zend_Controller_Action_Helper_AjaxContext */
        $ajaxContext->addActionContext('get', 'json')
            ->initContext();
    }

    public function indexAction()
    {
        /* @var $db Zend_Db_Adapter_Pdo_Abstract */
        $db = $this->getInvokeArg('bootstrap')
            ->getResource('multidb')
            ->getDb('ppmdb');

        $model = new TaxMapper($db);
        $taxes = $model->getTaxes();

        $this->view->taxes = $taxes;
    }

    public function createAction()
    {
        if (!empty($_POST))
        {
            $tax = new Tax();
            $tax->name = $_POST['name'];
            $tax->ratio = $_POST['ratio'];
            $tax->description = $_POST['desc'];

            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

            $model = new TaxMapper($db);
            $model->insert($tax);
        }

        $this->_forward('index');
    }

    public function updateAction()
    {
        if (!empty($_POST))
        {
            $tax = new Tax();
            $tax->id = $_POST['modid'];
            $tax->ratio = $_POST['modratio'];
            $tax->description = $_POST['moddesc'];

            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

            $model = new TaxMapper($db);
            $oldTax = $model->find($tax->id);

            if ($oldTax === NULL)
            {
                throw new RuntimeException();
            }

            $tax->name = $oldTax->name;
            $model->update($tax);
        }

        $this->_forward('index');
    }

    public function getAction()
    {
        $request = $this->getRequest();
        /* @var $request Zend_Controller_Request_Http */
        if (!$request->isXmlHttpRequest())
        {
            throw new RuntimeException();
        }

        $id = $request->getParam('id');

        if (NULL != $id)
        {
            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

            $model = new TaxMapper($db);
            $tax = $model->find($id);

            $this->view->id = $tax->id;
            $this->view->name = $tax->name;
            $this->view->ratio = $tax->ratio;
            $this->view->description = $tax->description;

        }
    }

}

