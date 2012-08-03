<?php

class Admin_ProviderController extends Zend_Controller_Action
{

    public function init()
    {
        require_once APPLICATION_PATH . '/models/Provider.php';
        require_once APPLICATION_PATH . '/models/ProviderMapper.php';

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

        $model = new ProviderMapper($db);
        $providers = $model->getProviders();

        $this->view->providerList = $providers;
    }

    public function createAction()
    {
        if (!empty($_POST))
        {
            $provider = new Provider();
            $provider->name = $_POST['name'];
            $provider->info = $_POST['info'];
            $provider->comment = $_POST['comment'];

            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

            $model = new ProviderMapper($db);
            $model->insert($provider);
        }

        $this->_forward('index');
    }

    public function updateAction()
    {
        if (!empty($_POST))
        {
            $provider = new Provider();
            $provider->id = $_POST['modid'];
            $provider->info = $_POST['modinfo'];
            $provider->comment = $_POST['modcomment'];

            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

            $model = new ProviderMapper($db);
            $oldProvider = $model->find($provider->id);
            if ($oldProvider === NULL)
            {
                throw new RuntimeException();
            }

            $provider->name = $oldProvider->name;
            $model->update($provider);
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

            $model = new ProviderMapper($db);
            $provider = $model->find($id);

            $this->view->id = $provider->id;
            $this->view->name = $provider->name;
            $this->view->info = $provider->info;
            $this->view->comment = $provider->comment;
        }
    }

}

