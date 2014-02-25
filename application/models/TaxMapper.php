<?php

/**
 *
 */

/**
 * Description of TaxMapper
 *
 * @author fabrice
 */
class TaxMapper
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
     * @return Tax[]
     */
    public function getTaxes()
    {
        $select = $this->_db->select()
            ->from('tax')
            ->order('name ASC');

        $query = $select->query();

        /* @var $taxes Tax[] */
        $taxes = array();
        while ($row = $query->fetch(Zend_Db::FETCH_OBJ))
        {
            $tax = new Tax();
            $tax->id = $row->id;
            $tax->name = $row->name;
            $tax->description = $row->description;
            $tax->ratio = $row->ratio;

            $taxes[] = $tax;
        }

        return $taxes;
    }

    /**
     *
     * @param integer $id
     *
     * @return Tax|null
     */
    public function find($id)
    {
        $select = $this->_db->select()
            ->from('tax')
            ->where('id = ?', $id, Zend_Db::BIGINT_TYPE);
        
        $query = $select->query();
        if (1 == $query->rowCount())
        {
            $row = $query->fetch(Zend_Db::FETCH_OBJ);
            $tax = new Tax();

            $tax->id = $row->id;
            $tax->name = $row->name;
            $tax->description = $row->description;
            $tax->ratio = $row->ratio;

            return $tax;
        }
        else
        {
            return NULL;
        }
    }

    public function insert(Tax $tax)
    {
        if (NULL !== $tax->id)
        {
            throw new RuntimeException();
        }

        $bind = array(
            'name' => $tax->name,
            'ratio' => $tax->ratio,
            'description' => $tax->description
        );

        $this->_db->insert('tax', $bind);
    }

    public function update(Tax $tax)
    {
        $select = $this->_db->select()
            ->from('tax', 'id')
            ->where('id = ?', $tax->id, Zend_Db::BIGINT_TYPE);

        $query = $select->query();
        if (1 !== $query->rowCount())
        {
            throw new RuntimeException();
        }

        $bind = array(
            'name' => $tax->name,
            'ratio' => $tax->ratio,
            'description' => $tax->description
        );

        $where['id = ?'] = $tax->id;

        $this->_db->update('tax', $bind, $where);
    }

}