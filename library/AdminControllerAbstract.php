<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminControllerAbstract
 * 
 * @property-read ArticleMapper $articleMapper Description
 * @property-read CartTrailer $cartTrailer Description
 * @property-read CategoryMapper $categoryMapper Description
 * @property-read PaymentMapper $paymentMapper Description
 * @property-read PromotionMapper $promotionMapper Description
 * @property-read ProviderMapper $providerMapper Description
 * @property-read TaxMapper $taxMapper Description
 * 
 * @author fabrice
 */
abstract class AdminControllerAbstract extends AbstractControllerAbstract
{

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
        'paymentMapper',
        'promotionMapper',
        'providerMapper',
        'taxMapper'
    );

}