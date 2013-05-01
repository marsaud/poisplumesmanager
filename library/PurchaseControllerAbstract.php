<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PurchaseControllerAbstract
 * 
 * @property-read PurchaseManager $purchaseManager Description
 * @property-read CashManager $cashManager Description
 * @property-read IncomeManager $incomeManager Description
 *
 * @author fabrice
 */
class PurchaseControllerAbstract extends AbstractControllerAbstract
{

    public function init()
    {
        parent::init();
        require_once APPLICATION_PATH . '/modules/purchase/models/AbstractMove.php';
        require_once APPLICATION_PATH . '/modules/purchase/models/Purchase.php';
        require_once APPLICATION_PATH . '/modules/purchase/models/PurchaseManager.php';
        require_once APPLICATION_PATH . '/modules/purchase/models/Income.php';
        require_once APPLICATION_PATH . '/modules/purchase/models/IncomeManager.php';
        require_once APPLICATION_PATH . '/modules/purchase/models/CashMove.php';
        require_once APPLICATION_PATH . '/modules/purchase/models/CashState.php';
        require_once APPLICATION_PATH . '/modules/purchase/models/CashManager.php';
        
    }

    /**
     *
     * @var PurchaseManager
     */
    protected $_purchaseManager;
    
    /**
     *
     * @var CashManager
     */
    protected $_cashManager;

    /**
     *
     * @var IncomeManager
     */
    protected $_incomeManager;
    
    /**
     *
     * @var string[]
     */
    protected $_models = array(
        'purchaseManager',
        'cashManager',
        'incomeManager'
    );

}