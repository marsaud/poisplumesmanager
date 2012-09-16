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
     * @var ArticlePromotionContainer
     */
    protected $_promos;

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

    public function __construct()
    {
        $this->_promos = new ArticlePromotionContainer();
    }
    
    public function getSalePrice()
    {
        return $this->tax->apply($this->getRawPrice());
    }

    public function getRawPrice()
    {
        return $this->price;
    }

    public function getPromotionPrice()
    {
        $price = $this->getSalePrice();
        $this->_promos->rewind();
        $promo = $this->_promos->current();
        return $promo ? $promo->apply($price) : $price;
    }

    public function getTaxAmount()
    {
        return $this->tax->evaluate($this->getRawPrice());
    }

    public function __set($name, $value)
    {
        switch ($name)
        {
            case 'promos':

                throw new LogicException('PromotionContainer cannot be set.');
                break;

            default:
                throw new OutOfRangeException('No ' . $name . ' write property');
                break;
        }
    }

    public function __get($name)
    {
        switch ($name)
        {
            case 'promos':

                return $this->_promos;
                break;

            default:
                throw new OutOfRangeException('No ' . $name . ' read property');
                break;
        };
    }

}