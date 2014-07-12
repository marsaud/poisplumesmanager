<?php

/**
 *
 */

/**
 * Description of Article
 *
 * @author fabrice
 * 
 * @property-read Category[] $categories
 * @property string $description
 * @property string $name
 * @property-read Promotion $onePromo
 * @property-write float $price
 * @property-read ArticlePromotionContainer $promos
 * @property float $quantity @see $soldQuantity
 * @property string $reference
 * @property float $soldQuantity
 * @property boolean $stock
 * @property float $stockedQuantity
 * @property Tax $tax
 * @property string $unit
 * 
 */
class Article extends PAF_Object_Base
{

    /**
     *
     * @var string
     */
    protected $_reference;

    /**
     *
     * @var string
     */
    protected $_name;

    /**
     *
     * @var string
     */
    protected $_description;

    /**
     * The full price seen by the customer
     *
     * @var float
     */
    protected $_price;

    /**
     *
     * @var Tax
     */
    protected $_tax;

    /**
     *
     * @var boolean
     */
    protected $_stock;

    /**
     *
     * @var Category[]
     */
    protected $_categories;

    /**
     *
     * @var ArticlePromotionContainer
     */
    protected $_promos;

    /**
     *
     * @var float
     */
    protected $_stockedQuantity;

    /**
     *
     * @var float
     */
    protected $_soldQuantity;

    /**
     *
     * @var string
     */
    protected $_unit;

    /**
     *
     * @var Provider
     */
    protected $_provider;

    public function __construct()
    {
        parent::__construct();

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
        return $this->tax->remove($this->_price);
    }

    /**
     * Prix TTC avec promotion
     * 
     * @return float
     */
    public function getFinalPrice()
    {
        $price = $this->_price;
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
        return $this->_price;
    }

    /**
     * Montant de la TVA
     * 
     * @return float
     */
    public function getTaxAmount()
    {
        return $this->getFinalPrice() - $this->getSalePrice();
    }

    /**
     * Retirer les catégories associées
     * 
     * @return void
     */
    public function freeCategories()
    {
        $this->_categories = array();
    }

    /**
     * Retirer toutes les promotions
     * 
     * @return void
     */
    public function freePromotions()
    {
        $this->_promos = new ArticlePromotionContainer();
    }
    
    /**
     * Associer à une catégorie
     * 
     * @param Category $category
     * 
     * @return void
     */
    public function addCategory(Category $category)
    {
        $this->_categories[] = $category;
    }

    protected function _initProperties()
    {
        $this->_extendProperties(array(
            'description' => 'description',
            'name' => 'name',
            'price' => 'price',
            'provider' => 'provider',
            'reference' => 'reference',
            'soldQuantity' => 'soldQuantity',
            /**
             * Retro-compatibility with a previous version of this class that 
             * has been serialized in production data.
             */
            'quantity' => 'soldQuantity',
            'stock' => 'stock',
            'stockedQuantity' => 'stockedQuantity',
            'tax' => 'tax',
            'unit' => 'unit'
        ));
        
        $this->_extendReadProperties(array(
            'categories' => 'categories',
            'onePromo' => 'onePromo',
            'promos' => 'promos'
        ));
        
        $this->_extendWriteProperties(array(
            'price' => 'price'
        ));
    }

    protected function _getOnePromo()
    {
        $this->promos->rewind();
        return $this->_promos->current();
    }
    
    protected function _setReference($reference)
    {
        if (!preg_match('^[A-Za-z0-9_+-]{1,45}$', $reference))
        {
            throw new PAF_Exception_IllegalArgument('Bad reference format');
        }
        else
        {
            $this->_reference = $reference;
        }
    }
    
    protected function _setTax(Tax $tax)
    {
        $this->_tax = $tax;
    }
    
    protected function _setProvider(Provider $provider)
    {
        $this->_provider = $provider;
    }
    
}
