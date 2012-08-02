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
            ->from('tva')
            ->order('name ASC');

        $query = $select->query();

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
            ->from('tva')
            ->where('id = ?', $id, Zend_Db::PARAM_INT);

        $query = $select->query();
        if ($query->rowCount() == 1)
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
        if ($tax->id !== NULL)
        {
            throw new RuntimeException();
        }

        $bind = array(
            'name' => $tax->name,
            'ratio' => $tax->ratio,
            'description' => $tax->description
        );

        $this->_db->insert('tva', $bind);
    }

    public function update(Tax $tax)
    {
        $select = $this->_db->select()
            ->from('tva', 'id')
            ->where('id = ?', $tax->id, Zend_Db::PARAM_INT);

        $query = $select->query();
        if ($query->rowCount() !== 1)
        {
            throw new RuntimeException();
        }

        $bind = array(
            'name' => $tax->name,
            'ratio' => $tax->ratio,
            'description' => $tax->description
        );

        $where['id = ?'] = $tax->id;

        $this->_db->update('tva', $bind, $where);
    }

}