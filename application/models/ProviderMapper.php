<?php

/**
 *
 */

/**
 * Description of ProviderMapper
 *
 * @author fabrice
 */
class ProviderMapper
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

    public function getProviders()
    {
        $select = $this->_db->select()
            ->from('provider')
            ->order('name ASC');

        $query = $select->query();

        /* @var $providers Provider[] */
        $providers = array();
        while ($row = $query->fetch(Zend_Db::FETCH_OBJ))
        {
            $provider = new Provider();

            $provider->id = $row->id;
            $provider->name = $row->name;
            $provider->info = $row->info;
            $provider->comment = $row->comment;

            $providers[] = $provider;
        }

        return $providers;
    }

    /**
     *
     * @param integer $id
     *
     * @return Provider|null
     */
    public function find($id)
    {
        $select = $this->_db->select()
            ->from('provider')
            ->where('id = ?', $id, Zend_Db::BIGINT_TYPE);

        $query = $select->query();

        if (1 == $query->rowCount())
        {
            $row = $query->fetch(Zend_Db::FETCH_OBJ);
            $provider = new Provider();

            $provider->id = $row->id;
            $provider->name = $row->name;
            $provider->info = $row->info;
            $provider->comment = $row->comment;

            return $provider;
        }
        else
        {
            return NULL;
        }
    }

    public function insert(Provider $provider)
    {
        if (NULL !== $provider->id)
        {
            throw new RuntimeException();
        }

        $bind = array(
            'name' => $provider->name,
            'info' => $provider->info,
            'comment' => $provider->comment
        );

        $this->_db->insert('provider', $bind);
    }

    public function update(Provider $provider)
    {
        $select = $this->_db->select()
            ->from('provider', 'id')
            ->where('id = ?', $provider->id, Zend_Db::BIGINT_TYPE);

        $query = $select->query();
        if (1 !== $query->rowCount())
        {
            throw new RuntimeException();
        }

        $bind = array(
            'name' => $provider->name,
            'info' => $provider->info,
            'comment' => $provider->comment
        );

        $where['id = ?'] = $provider->id;

        $this->_db->update('provider', $bind, $where);
    }

}