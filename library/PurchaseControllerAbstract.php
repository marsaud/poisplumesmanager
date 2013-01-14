<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PurchaseControllerAbstract
 * 
 * @property-read PurchaseManager $purchaseManager Description
 *
 * @author fabrice
 */
class PurchaseControllerAbstract extends AbstractControllerAbstract
{

    public function init()
    {
        parent::init();
        require_once APPLICATION_PATH . '/modules/purchase/models/Purchase.php';
        require_once APPLICATION_PATH . '/modules/purchase/models/PurchaseManager.php';
    }

    /**
     *
     * @var PurchaseManager
     */
    protected $_purchaseManager;

    /**
     *
     * @var string[]
     */
    protected $_models = array(
        'purchaseManager'
    );

}