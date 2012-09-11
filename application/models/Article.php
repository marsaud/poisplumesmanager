<?php

/**
 *
 */

/**
 * Description of Article
 *
 * @author fabrice
 */
class Article
{

    /**
     *
     * @var string
     */
    public $reference;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var float
     */
    public $price;

    /**
     *
     * @var Tax
     */
    public $tax;

    /**
     *
     * @var boolean
     */
    public $stock;

    /**
     *
     * @var Category[]
     */
    public $categories = array();

    /**
     *
     * @var Promotion[]
     */
    public $promos = array();

    /**
     *
     * @var float
     */
    public $quantity;

    /**
     *
     * @var string
     */
    public $unit;

    /**
     *
     * @var Provider
     */
    public $provider;

    public function getSalePrice()
    {
        return $this->tax->apply($this->price);
    }

    public function getRawPrice()
    {
        return $this->price;
    }

    public function getPromotionPrice()
    {
        $price = $this->getSalePrice();
        /* @var $promo Promotion */
        $promo = array_pop($this->promos);
        return $promo ? $promo->apply($price) : $price;
    }

}