<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initCurrency()
    {
        $currency = new Zend_Currency('fr_FR');
        Zend_Registry::set('Zend_Currency', $currency);

        return $currency;
    }

    protected function _initPlugins()
    {
        /* @var $frontController Zend_Controller_Front */
        $frontController = $this->bootstrap('frontcontroller')
                ->getResource('frontcontroller');
        
        $frontController->registerPlugin(new LayoutManagerPlugin());
    }
}

