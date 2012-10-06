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
     * @return integer AUTO_INCREMENT PK
     */
    public function save(array $soldArticles)
    {
        /**
         * @todo On va stocker cela en serialisé, ça va le faire à mort
         */
    }

}