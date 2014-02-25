<?php

/**
 *
 */

/**
 * Description of Article
 *
 * @author fabrice
 * 
 * @property-read ArticlePromotionContainer $promos
 * @property-read Promotion $onePromo
 * @property float $quantity Description
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
     * The full price seen by the customer
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
    public $categories;

    /**
     *
     * @var ArticlePromotionContainer
     */
    protected $_promos;

    /**
     *
     * @var float
     */
    public $stockedQuantity;

    /**
     *
     * @var float
     */
    public $soldQuantity;

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
        $this->freeCategories();
        $this->freePromotions();
    }

    /**
     * Prix hors taxe avec promotion
     * 
     * @return float
     */
    public function getSalePrice()
    {
        $price = $this->getRawPrice();
        $promo = $this->onePromo;
        return $promo ? $promo->apply($price) : $price;
    }

    /**
     * Prix hors taxe catalogue 
     * 
     * @return float
     */
    public function getRawPrice()
    {
        return $this->tax->remove($this->price);
    }

    /**
     * Prix TTC avec promotion
     * 
     * @return float
     */
    public function getFinalPrice()
    {
        $price = $this->price;
        $promo = $this->onePromo;
        return $promo ? $promo->apply($price) : $price;
    }

    /**
     * Prix TTC catalogue
     * 
     * @return float
     */
    public function getFrontPrice()
    {
        return $this->price;
    }

    public function getTaxAmount()
    {
        return $this->getFinalPrice() - $this->getSalePrice();
    }

    public function __set($name, $value)
    {
        switch ($name)
        {
            case 'quantity':
                /**
                 * Retro-compatibility with a previous version of this class that has been
                 * serialized in production data.
                 */
                $this->soldQuantity = $value;
                break;

            case 'promos':
                throw new LogicException('PromotionContainer cannot be set.');

            default:
                throw new OutOfRangeException('No ' . $name . ' write property');
        }
    }

    public function __get($name)
    {
        switch ($name)
        {
            case 'promos':
                return $this->_promos;

            case 'onePromo':
                $this->_promos->rewind();
                return $this->_promos->current();

            case 'quantity':
                return $this->soldQuantity;

            default:
                throw new OutOfRangeException('No ' . $name . ' read property');
        }
    }

    public function freeCategories()
    {
        $this->categories = array();
    }

    public function freePromotions()
    {
        $this->_promos = new ArticlePromotionContainer();
    }

}
