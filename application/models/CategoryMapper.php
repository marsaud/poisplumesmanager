<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CategoryMapper
 *
 * @author fabrice
 */
class CategoryMapper
{

    /**
     *
     * @var Zend_Db_Adapter_Pdo_Abstract
     */
    protected $_db;

    public function __construct(Zend_Db_Adapter_Pdo_Abstract $_db)
    {
        $this->_db = $_db;
    }

    /**
     *
     * @return Category[]
     */
    public function getCategoryTree()
    {
        $select = $this->_db->select()
            ->from('category')
            ->order('category_ref ASC');

        $query = $select->query();

        /* @var $categoryTree Category[] */
        $categoryTree = array();
        while ($row = $query->fetch(Zend_Db::FETCH_OBJ))
        {
            $category = new Category();
            $category->reference = $row->ref;
            $category->name = $row->name;
            $category->description = $row->desc;

            if ($row->category_ref == NULL)
            {
                $categoryTree[$category->reference] = $category;
            }
            else
            {
                $categoryTree[$row->category_ref][] = $category;
            }
        }

        return $categoryTree;
    }

    /**
     *
     * @param string $reference
     * @return Category|null
     */
    public function find($reference)
    {
        $select = $this->_db->select()
            ->from('category', array('ref', 'name', 'desc'))
            ->where('ref = ?', $reference);

        $query = $select->query();
        if ($query->rowCount() == 1)
        {
            $row = $query->fetch(Zend_Db::FETCH_OBJ);

            $category = new Category();
            $category->reference = $row->ref;
            $category->name = $row->name;
            $category->description = $row->desc;

            $this->_loadSubCategories($category);

            return $category;
        }
        else
        {
            return NULL;
        }
    }

    /**
     *
     * @param Category $category
     *
     * @return void
     */
    protected function _loadSubCategories(Category $category)
    {
        $select = $this->_db->select()
            ->from('category')
            ->where('category_ref = ?', $category->reference);

        $query = $select->query();
        while ($row = $query->fetch(Zend_Db::FETCH_OBJ))
        {
            $subCategory = new Category();
            $subCategory->reference = $row->ref;
            $subCategory->name = $row->name;
            $subCategory->description = $row->desc;

            $category[] = $subCategory;
        }
    }

    /**
     *
     * @param Category $category
     *
     * @return Category
     *
     * @throws RuntimeException
     */
    public function findParent(Category $category)
    {
        $select = $this->_db->select()
            ->from('category', 'category_ref')
            ->where('ref = ?', $category->reference);

        $query = $select->query();
        if ($query->rowCount() != 1)
        {
            throw new RuntimeException();
        }

        $ref = $query->fetchColumn(0);
        if ($ref != NULL)
        {
            return $this->find($ref);
        }
        else
        {
            return NULL;
        }
    }

    /**
     *
     * @param Category $category
     * @param string $parentReference
     *
     * @return void
     *
     * @throws RuntimeException
     */
    public function insert(Category $category, $parentReference = NULL)
    {
        $bind = array(
            'ref' => $category->reference,
            'name' => $category->name,
            'desc' => $category->description
        );

        if ($parentReference !== NULL)
        {
            $select = $this->_db
                ->select()
                ->from('category', 'ref')
                ->where('ref = ?', $parentReference);

            $query = $select->query();
            if ($query->rowCount() !== 1)
            {
                throw new RuntimeException();
            }

            $bind['category_ref'] = $parentReference;
        }

        $this->_db->insert('category', $bind);
    }

    /**
     *
     * @param Category $category
     * @param string $parentReference
     *
     * @return void
     *
     * @throws RuntimeException
     */
    public function update(Category $category, $parentReference = NULL)
    {
        $bind = array(
            'name' => $category->name,
            'desc' => $category->description
        );

        $where['ref = ?'] = $category->reference;

        if ($parentReference !== NULL)
        {
            $select = $this->_db
                ->select()
                ->from('category', 'ref')
                ->where('ref = ?', $parentReference);

            $query = $select->query();
            if ($query->rowCount() !== 1)
            {
                throw new RuntimeException();
            }

            $bind['category_ref'] = $parentReference;
        }

        $this->_db->update('category', $bind, $where);
    }

}