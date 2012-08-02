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
                $categoryTree[$row->category_ref]->subCategories[] = $category;
            }
        }

        return $categoryTree;
    }

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