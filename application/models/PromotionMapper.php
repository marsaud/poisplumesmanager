<?php

/**
 *
 */

/**
 * Description of PromotionMapper
 *
 * @author fabrice
 */
class PromotionMapper
{
    /**
     *
     * @var Zend_Db_Adapter_Pdo_Abstract
     */
    protected $_db;

    public function __construct(Zend_Db_Adapter_Pdo_Abstract $db)
    {
        $this->_db = $db;
    }

    /**
     *
     * @return Promotion[]
     */
    public function getPromotions()
    {
        $select = $this->_db->select()
            ->from('promo')
            ->order('name ASC');

        $query = $select->query();

        /* @var $promotions Promotion[] */
        $promotions = array();
        while ($row = $query->fetch(Zend_Db::FETCH_OBJ))
        {
            $promo = new Promotion();
            $promo->id = $row->id;
            $promo->name = $row->name;
            $promo->description = $row->description;
            $promo->ratio = $row->ratio;

            $promotions[] = $promo;
        }

        return $promotions;
    }

    /**
     *
     * @param integer $id
     *
     * @return Promotion|null
     */
    public function find($id)
    {
        $select = $this->_db->select()
            ->from('promo')
            ->where('id = ?', $id, Zend_Db::BIGINT_TYPE);

        $query = $select->query();
        if (1 == $query->rowCount())
        {
            $row = $query->fetch(Zend_Db::FETCH_OBJ);
            $promo = new Promotion();

            $promo->id = $row->id;
            $promo->name = $row->name;
            $promo->description = $row->description;
            $promo->ratio = $row->ratio;

            return $promo;
        }
        else
        {
            return NULL;
        }
    }

    public function insert(Promotion $promotion)
    {
        if (NULL !== $promotion->id)
        {
            throw new RuntimeException();
        }

        $bind = array(
            'name' => $promotion->name,
            'ratio' => $promotion->ratio,
            'description' => $promotion->description
        );

        $this->_db->insert('promo', $bind);
    }

    public function update(Promotion $promotion)
    {
        $select = $this->_db->select()
            ->from('promo', 'id')
            ->where('id = ?', $promotion->id, Zend_Db::BIGINT_TYPE);

        $query = $select->query();
        if (1 !== $query->rowCount())
        {
            throw new RuntimeException();
        }

        $bind = array(
            'name' => $promotion->name,
            'ratio' => $promotion->ratio,
            'description' => $promotion->description
        );

        $where['id = ?'] = $promotion->id;

        $this->_db->update('promo', $bind, $where);
    }
}