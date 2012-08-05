<?php

class Admin_PromotionController extends Zend_Controller_Action
{

    public function init()
    {
        require_once APPLICATION_PATH . '/models/Promotion.php';
        require_once APPLICATION_PATH . '/models/PromotionMapper.php';

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

        $model = new PromotionMapper($db);
        $promotions = $model->getPromotions();

        $this->view->promos = $promotions;
    }

    public function createAction()
    {
        if (!empty($_POST))
        {
            $promo = new Promotion();
            $promo->name = $_POST['name'];
            $promo->ratio = $_POST['ratio'];
            $promo->description = $_POST['desc'];

            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

            $model = new PromotionMapper($db);
            $model->insert($promo);
        }

        $this->_forward('index');
    }

    public function updateAction()
    {
        if (!empty($_POST))
        {
            $promo = new Promotion();
            $promo->id = $_POST['modid'];
            $promo->ratio = $_POST['modratio'];
            $promo->description = $_POST['moddesc'];

            /* @var $db Zend_Db_Adapter_Pdo_Abstract */
            $db = $this->getInvokeArg('bootstrap')
                ->getResource('multidb')
                ->getDb('ppmdb');

            $model = new PromotionMapper($db);
            $oldPromo = $model->find($promo->id);

            if ($oldPromo === NULL)
            {
                throw new RuntimeException();
            }

            $promo->name = $oldPromo->name;
            $model->update($promo);
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

            $model = new PromotionMapper($db);
            $promo = $model->find($id);

            $this->view->id = $promo->id;
            $this->view->name = $promo->name;
            $this->view->ratio = $promo->ratio;
            $this->view->description = $promo->description;

        }
    }


}

