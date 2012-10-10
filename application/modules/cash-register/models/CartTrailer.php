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
        
        $date = new DateTime();
        $dateString = $date->format('Y-m-d H:i:s');
        
        $hash = md5($cart . $dateString);
        
        $bind = array(
            'hash' => $hash,
            'cart' => $cart,
            'date' => $dateString,
            'payed' => false
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
                ->from('carttrailer', array('cart'))
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
    
    /**
     * @todo
     * 
     * @param type $hash
     */
    public function pay($hash)
    {
        
    }
    
    /**
     * 
     * @param type $hash
     */
    public function cancel($hash)
    {
        
    }
}