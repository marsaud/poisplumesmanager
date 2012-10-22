<?php

class Admin_ProviderController extends AdminControllerAbstract
{

    public function init()
    {
//        require_once APPLICATION_PATH . '/models/Provider.php';
//        require_once APPLICATION_PATH . '/models/ProviderMapper.php';

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        /* @var $ajaxContext Zend_Controller_Action_Helper_AjaxContext */
        $ajaxContext->addActionContext('get', 'json')
            ->initContext();
    }

    public function indexAction()
    {
        $providers = $this->providerMapper->getProviders();
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

            $this->providerMapper->insert($provider);
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

            $oldProvider = $this->providerMapper->find($provider->id);
            if ($oldProvider === NULL)
            {
                throw new RuntimeException();
            }

            $provider->name = $oldProvider->name;
            $this->providerMapper->update($provider);
        }

        $this->_forward('index');
    }

    public function getAction()
    {
        $request = $this->getRequest();
        /* @var $request Zend_Controller_Request_Http */
        if (!$request->isXmlHttpRequest())
        {
            throw new RuntimeException('Wrong Request Context');
        }

        $id = $request->getParam('id');

        if (NULL != $id)
        {
            $provider = $this->providerMapper->find($id);

            $this->view->id = $provider->id;
            $this->view->name = $provider->name;
            $this->view->info = $provider->info;
            $this->view->comment = $provider->comment;
        }
    }

}

