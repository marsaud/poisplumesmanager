<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CartTrailer
 *
 * @author fabrice
 */
class CartTrailer
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
     * @param Article[] $soldArticles
     * 
     * @return string hash
     */
    public function save(array $soldArticles)
    {
        $cart = serialize($soldArticles);
        $hash = md5($cart);
        $bind = array(
            'hash' => $hash,
            'cart' => $cart
        );
        $this->_db->insert('carttrailer', $bind);
        
        return $hash;
    }
    
    /**
     * 
     * @param string $hash
     * 
     * @return Article[]
     */
    public function get($hash)
    {
        $select = $this->_db->select()
                ->from('carttrail', array('cart'))
                ->where('hash = ?', $hash)
                ;
        
        $query = $select->query();
        if ($query->rowCount() == 0)
        {
            return null;
        }
        else
        {
            $cart = $query->fetchColumn();
            return unserialize($cart);
        }
    }

}