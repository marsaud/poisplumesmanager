<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbstractControllerAbstract
 *
 * @property-read ArticleMapper $articleMapper Description
 * @property-read CategoryMapper $categoryMapper Description
 * @property-read PromotionMapper $promotionMapper Description
 * @property-read ProviderMapper $providerMapper Description
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
     * @var CategoryMapper
     */
    protected $_categoryMapper;

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
     * @var TaxMapper
     */
    protected $_taxMapper;
    
    /**
     *
     * @var string[]
     */
    protected $_models = array(
        'articleMapper',
        'categoryMapper',
        'promotionMapper',
        'providerMapper',
        'taxMapper'
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
                        ->getResource('multidb')
                        ->getDb('ppmdb');
            }
            
            return $this->_db;
        }

        if (!in_array($name, $this->_models))
        {
            throw new OutOfRangeException("No read property : '$name'");
        }

        if (NULL === $this->{'_' . $name})
        {
            $className = ucfirst($name);
            $this->{'_' . $name} = new $className($this->db);
        }

        return $this->{'_' . $name};
    }

}