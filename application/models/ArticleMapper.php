<?php

/**
 *
 */

/**
 * Description of ArticleMapper
 *
 * @author fabrice
 */
class ArticleMapper
{
    
    const MINIMUM_SEARCH_SIZE = 4;
    
    const STOCK_MANAGED_ONLY = true;
    const ALL = false;

    /**
     *
     * @var Zend_Db_Adapter_Pdo_Abstract
     */
    protected $_db;

    /**
     *
     * @var CategoryMapper
     */
    protected $_categoryModel;

    /**
     *
     * @var PromotionMapper
     */
    protected $_promoModel;

    /**
     *
     * @var ProviderMapper
     */
    protected $_providerModel;
    
    /**
     *
     * @var TaxMapper
     */
    protected $_taxModel;

    public function __construct(Zend_Db_Adapter_Pdo_Abstract $db)
    {
        $this->_db = $db;
    }

    /**
     * @param string $categoryRef
     * @param boolean $mode
     * 
     * @return Article[]
     */
    public function getArticles($categoryRef = NULL, $mode = self::ALL)
    {
        $select = $this->_db->select()
                ->from(array('a' => 'article'))
                ->joinLeft(
                        array('ca' => 'categoryarticle')
                        , 'ca.article_ref = a.ref'
                        , array('ca.category_ref')
                )
                ->joinLeft(array('pa' => 'promoarticle')
                        , 'pa.article_ref = a.ref'
                        , array('pa.promo_id'))
                ->joinLeft(
                        array('ap' => 'articleprovider')
                        , 'ap.article_ref = a.ref'
                        , array('ap.provider_id')
                )
                ->order(array('ref ASC', 'category_ref ASC'));

        if (NULL !== $categoryRef)
        {
            $select->where('ca.category_ref = ?', $categoryRef);
        }

        if (self::STOCK_MANAGED_ONLY === $mode)
        {
            $select->where('stocked = ?', true);
        }

        /* @var $articles Article[] */
        $articles = array();
        $query = $select->query();
        if ($query->rowCount() > 0)
        {
            $article = NULL;
            while ($row = $query->fetch(Zend_Db::FETCH_OBJ))
            {
                if (empty($article) || ($row->ref != $article->reference))
                {
                    $article = new Article();
                    $article->reference = $row->ref;
                    $article->name = $row->name;
                    $article->description = $row->description;
                    $article->price = $row->priceht;
                    $article->stock = $row->stocked;
                    $article->unit = $row->unit;
                    $article->stockedQuantity = $row->qty;

                    $article->tax = $this->_getTaxModel()->find($row->tax_id);

                    $articles[] = $article;
                }

                if (NULL !== $row->provider_id)
                {
                    $provider = $this->_getProviderModel()->find(
                            $row->provider_id
                    );
                    $article->provider = $provider;
                }

                if (NULL !== $row->promo_id)
                {
                    $promo = $this->_getPromotionModel()->find($row->promo_id);
                    $article->promos[] = $promo;
                }

                if (NULL !== $row->category_ref)
                {
                    $category = $this->_getCategoryModel()->find(
                            $row->category_ref
                    );
                    $article->categories[] = $category;
                }
            }
        }
        return $articles;
    }

    /**
     *
     * @param string $reference
     *
     * @return Article|null
     */
    public function find($reference)
    {
        $select = $this->_db->select()
                ->from(array('a' => 'article'))
                ->joinLeft(
                        array('ca' => 'categoryarticle')
                        , 'ca.article_ref = a.ref'
                        , array('ca.category_ref')
                )
                ->joinLeft(array('pa' => 'promoarticle')
                        , 'pa.article_ref = a.ref'
                        , array('pa.promo_id'))
                ->joinLeft(
                        array('ap' => 'articleprovider')
                        , 'ap.article_ref = a.ref'
                        , array('ap.provider_id')
                )
                ->where('a.ref = ?', $reference)
                ->order(array('category_ref ASC'));

        $query = $select->query();
        if ($query->rowCount() > 0)
        {
            $article = NULL;
            while ($row = $query->fetch(Zend_Db::FETCH_OBJ))
            {
                if (!isset($article))
                {
                    $article = new Article();
                    $article->reference = $row->ref;
                    $article->name = $row->name;
                    $article->description = $row->description;
                    $article->price = $row->priceht;
                    $article->stock = $row->stocked;
                    $article->unit = $row->unit;
                    $article->stockedQuantity = $row->qty;

                    $article->tax = $this->_getTaxModel()->find($row->tax_id);
                }

                if (NULL !== $row->category_ref)
                {
                    $category = $this->_getCategoryModel()->find(
                            $row->category_ref
                    );
                    $article->categories[] = $category;
                }

                if (NULL !== $row->promo_id)
                {
                    $promo = $this->_getPromotionModel()->find($row->promo_id);
                    $article->promos[] = $promo;
                }

                if (NULL !== $row->provider_id)
                {
                    $provider = $this->_getProviderModel()->find(
                            $row->provider_id
                    );
                    $article->provider = $provider;
                }
            }

            return $article;
        }
        else
        {
            return NULL;
        }
    }

    /**
     *
     * @return CategoryMapper
     */
    protected function _getCategoryModel()
    {
        if (NULL === $this->_categoryModel)
        {
            $this->_categoryModel = new CategoryMapper($this->_db);
        }

        return $this->_categoryModel;
    }

    /**
     *
     * @return ProviderMapper
     */
    protected function _getProviderModel()
    {
        if (NULL === $this->_providerModel)
        {
            $this->_providerModel = new ProviderMapper($this->_db);
        }

        return $this->_providerModel;
    }

    /**
     *
     * @return PromotionMapper
     */
    protected function _getPromotionModel()
    {
        if (NULL === $this->_promoModel)
        {
            $this->_promoModel = new PromotionMapper($this->_db);
        }

        return $this->_promoModel;
    }
    
    protected function _getTaxModel()
    {
        if (NULL === $this->_taxModel)
        {
            $this->_taxModel = new TaxMapper($this->_db);
        }
        
        return $this->_taxModel;
    }

    public function create(Article $article)
    {
        $bind = array(
            'ref' => $article->reference,
            'name' => $article->name,
            'description' => $article->description,
            'tax_id' => $article->tax->id,
            'priceht' => $article->getFrontPrice(),
            'stocked' => $article->stock,
            'qty' => 0,
            'unit' => $article->unit
        );

        try
        {
            $this->_db->insert('article', $bind);
            foreach ($article->categories as $category)
            {
                $this->_db->insert('categoryarticle', array(
                    'article_ref' => $article->reference,
                    'category_ref' => $category->reference
                ));
            }
            foreach ($article->promos as $promo)
            {
                $this->_db->insert('promoarticle', array(
                    'article_ref' => $article->reference,
                    'promo_id' => $promo->id
                ));
            }
            if ($article->provider != NULL)
            {
                $this->_db->insert('articleprovider', array(
                    'provider_id' => $article->provider->id,
                    'article_ref' => $article->reference
                ));
            }
        }
        catch (Exception $exc)
        {
            throw $exc;
        }
    }

    public function update(Article $article)
    {
        $bind = array(
            'name' => $article->name,
            'description' => $article->description,
            'tax_id' => $article->tax->id,
            'priceht' => $article->getFrontPrice(),
            'stocked' => $article->stock,
            'qty' => $article->stockedQuantity,
            'unit' => $article->unit
        );

        try
        {
            $whereUpdate['ref = ?'] = $article->reference;
            $this->_db->update('article', $bind, $whereUpdate);

            $whereDelete['article_ref = ?'] = $article->reference;

            $this->_db->delete('categoryarticle', $whereDelete);
            foreach ($article->categories as $category)
            {
                $this->_db->insert('categoryarticle', array(
                    'article_ref' => $article->reference,
                    'category_ref' => $category->reference
                ));
            }
            $this->_db->delete('promoarticle', $whereDelete);
            foreach ($article->promos as $promo)
            {
                $this->_db->insert('promoarticle', array(
                    'article_ref' => $article->reference,
                    'promo_id' => $promo->id
                ));
            }

            $this->_db->delete('articleprovider', $whereDelete);
            if (NULL !== $article->provider)
            {
                $this->_db->insert('articleprovider', array(
                    'provider_id' => $article->provider->id,
                    'article_ref' => $article->reference
                ));
            }
        }
        catch (Exception $exc)
        {
            throw $exc;
        }
    }

    /**
     * 
     * @param string $search
     */
    public function search($search)
    {
        if (strlen($search) < self::MINIMUM_SEARCH_SIZE)
        {
            throw new Exception('Search results may be too heavy');
        }
        
        $select = $this->_db->select()
                ->from('article', array('ref', 'name'))
                ->where('ref like ?', new Zend_Db_Expr("'%$search%'"))
                ->orWhere('name like ?', new Zend_Db_Expr("'%$search%'"))
                ->order('ref ASC');
        
        $query = $select->query();
        
        $results = $query->fetchAll(Zend_Db::FETCH_OBJ);
        
        return $results;
    }

}
