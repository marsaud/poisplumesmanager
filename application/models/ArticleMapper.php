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

    public function __construct(Zend_Db_Adapter_Pdo_Abstract $db)
    {
        $this->_db = $db;
    }

    /**
     *
     * @return Article[]
     */
    public function getArticles()
    {
        $select = $this->_db->select()
            ->from(array('a' => 'article'))
            ->joinLeft(array('ca' => 'categoryarticle'), 'ca.article_ref = a.ref', array())
            ->joinLeft(array('c' => 'category'), 'c.ref = ca.category_ref', array('cref' => 'ref'))
            ->order(array('ref ASC', 'cref ASC'));

        $articles = array();
        $query = $select->query();
        if ($query->rowCount() > 0)
        {
            while ($row = $query->fetch(Zend_Db::FETCH_OBJ))
            {
                if (!isset($article) || ($row->ref != $article->reference))
                {
                    $article = new Article();
                    $article->reference = $row->ref;
                    $article->name = $row->name;
                    $article->description = $row->description;
                    $article->price = $row->priceht;
                    $article->stock = $row->stocked;
                    if ($article->stock)
                    {
                        $article->unit = $row->unit;
                        $article->quantity = $row->qty;
                    }

                    $taxModel = new TaxMapper($this->_db);
                    $article->tax = $taxModel->find($row->tax_id);

                    $articles[] = $article;
                }

                if ($row->cref != NULL)
                {
                    $category = $this->_getCategoryModel()->find($row->cref);
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
            ->joinLeft(array('ca' => 'categoryarticle'), 'ca.article_ref = a.ref', array())
            ->joinLeft(array('c' => 'category'), 'c.ref = ca.category_ref', array('cref' => 'ref'))
            ->where('a.ref = ?', $reference)
            ->order(array('cref ASC'));

        $query = $select->query();
        if ($query->rowCount() > 0)
        {
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
                    if ($article->stock)
                    {
                        $article->unit = $row->unit;
                        $article->quantity = $row->qty;
                    }

                    $taxModel = new TaxMapper($this->_db);
                    $article->tax = $taxModel->find($row->tax_id);
                }

                if ($row->cref != NULL)
                {
                    $category = $this->_getCategoryModel()->find($row->cref);
                    $article->categories[] = $category;
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

    public function create(Article $article)
    {
        $bind = array(
            'ref' => $article->reference,
            'name' => $article->name,
            'description' => $article->description,
            'tax_id' => $article->tax->id,
            'priceht' => $article->price,
            'stocked' => $article->stock,
        );

        if ($article->stock)
        {
            $bind['qty'] = 0;
            $bind['unit'] = $article->unit;
        }

        $this->_db->beginTransaction();
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
            $this->_db->commit();
        }
        catch (Exception $exc)
        {
            $this->_db->rollBack();
            throw $exc;
        }
    }

    public function update(Article $article)
    {
        $bind = array(
            'name' => $article->name,
            'description' => $article->description,
            'tax_id' => $article->tax->id,
            'priceht' => $article->price,
            'stocked' => $article->stock,
        );

        if ($article->stock)
        {
            $bind['unit'] = $article->unit;
        }
        else
        {
            $bind['qty'] = NULL;
            $bind['unit'] = NULL;
        }

        $this->_db->beginTransaction();
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
            $this->_db->commit();
        }
        catch (Exception $exc)
        {
            $this->_db->rollBack();
            throw $exc;
        }
    }

}