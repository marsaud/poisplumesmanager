<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StockManager
 *
 * @author fabrice
 */
class StockManager
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

    public function update($reference, $quantity, $comment)
    {
        $articleMapper = new ArticleMapper($this->_db);
        $article = $articleMapper->find($reference);

        if (!is_numeric($quantity))
        {
            throw new Exception('Quantity must be a number');
        }

        /**
         * @todo SECURISER LES ACCES CONCURRENTS
         */
        $oldQuantity = $article->quantity;
        $article->quantity = (float) $quantity;
        $this->_db->beginTransaction();
        try
        {
            $articleMapper->update($article);
            $this->trail($this->_db, $article, $oldQuantity, $comment);
            $this->_db->commit();
        }
        catch (Exception $exc)
        {
            $this->_db->rollBack();
            throw $exc;
        }
    }

    public function trail(Zend_Db_Adapter_Pdo_Abstract $db, Article $article, $oldQuantity, $comment)
    {
        $date = new DateTime();
        $dateString = $date->format('Y-m-d H:i:s');
        
        $bind = array(
            'articleref' => $article->reference,
            'articlename' => $article->name,
            'previous' => $oldQuantity,
            'modif' => ($article->quantity - $oldQuantity),
            'new' => $article->quantity,
            'unit' => $article->unit,
            'date' => $dateString,
            'user' => $_SERVER['PHP_AUTH_USER'],
            'comment' => $comment
        );
        
        $db->insert('stocktrail', $bind);
    }

}