<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbstractControllerAbstract
 *
 * @property-read ArticleMapper $articleMapper Description
 * @property-read CartTrailer $cartTrailer Description
 * @property-read CategoryMapper $categoryMapper Description
 * @property-read OperationMapper $operationMapper Description
 * @property-read OperationManager $operationManager Description
 * @property-read PaymentMapper $paymentMapper Description
 * @property-read PromotionMapper $promotionMapper Description
 * @property-read ProviderMapper $providerMapper Description
 * @property-read StockManager $stockManager Description
 * @property-read TaxMapper $taxMapper Description
 * @property-read Zend_Db_Adapter_Pdo_Abstract $db Description
 * 
 * @author fabrice
 */
abstract class AbstractControllerAbstract extends Zend_Controller_Action
{

    /**
     *
     * @var Zend_Db_Adapter_Pdo_Abstract
     */
    protected $_db;

    /**
     *
     * @var ArticleMapper
     */
    protected $_articleMapper;

    /**
     *
     * @var CartTrailer
     */
    protected $_cartTrailer;

    /**
     *
     * @var CategoryMapper
     */
    protected $_categoryMapper;

    /**
     *
     * @var OperationManager
     */
    protected $_operationManager;

    /**
     *
     * @var OperationMapper
     */
    protected $_operationMapper;

    /**
     *
     * @var PaymentMapper
     */
    protected $_paymentMapper;

    /**
     *
     * @var PromotionMapper
     */
    protected $_promotionMapper;

    /**
     *
     * @var ProviderMapper
     */
    protected $_providerMapper;

    /**
     *
     * @var StockManager
     */
    protected $_stockManager;

    /**
     *
     * @var TaxMapper
     */
    protected $_taxMapper;

    /**
     *
     * @var string[]
     */
    protected $_models = array(
        'articleMapper',
        'cartTrailer',
        'categoryMapper',
        'operationMapper',
        'paymentMapper',
        'promotionMapper',
        'providerMapper',
        'stockManager',
        'taxMapper'
    );
    protected $_plains = array(
        'operationManager'
    );

    /**
     * 
     * @param string $name
     * 
     * @return mixed
     * 
     * @throws OutOfRangeException
     */
    public function __get($name)
    {
        if ($name == 'db')
        {
            if (NULL === $this->_db)
            {
                $this->_db = $this->getInvokeArg('bootstrap')
                        ->bootstrap('multidb')
                        ->getResource('multidb')
                        ->getDb('ppmdb');
            }

            return $this->_db;
        }
        elseif (!in_array($name, array_merge($this->_models, $this->_plains)))
        {
            throw new OutOfRangeException("No read property : '$name'");
        }
        else
        {
            if (NULL === $this->{'_' . $name})
            {
                $className = ucfirst($name);
                if (in_array($name, $this->_models))
                {
                    $this->{'_' . $name} = new $className($this->db);
                }
                else
                {
                    $this->{'_' . $name} = new $className();
                }
            }

            return $this->{'_' . $name};
        }
    }

}
