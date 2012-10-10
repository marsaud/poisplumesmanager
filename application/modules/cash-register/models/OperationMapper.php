<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OperationMapper
 *
 * @author fabrice
 */
class OperationMapper
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
     * @param string $hash
     * @param Article[] $soldArticles
     * @param Payment[] $payments
     */
    public function record($hash, array $soldArticles, array $payments)
    {
        $totalRawPrice = array();
        $totalSalePrice = 0;
        $totalTax = array();

        $operationManager = new OperationManager();
        $operationManager->compute($soldArticles, $totalRawPrice, $totalTax, $totalSalePrice);
        
        $totalPaid = Payment::consolidate($payments);
        if ($totalPaid !== $totalSalePrice)
        {
            throw new Exception('WRONG TOTAL CONSOLIDATION');
        }
        
        /**
         * @todo Créer une ligne qui "résume" l'opération
         */
        
        /**
         * @todo Créer les lignes d'articles qui détaillent l'opération
         */
        
        /**
         * @todo Passer le panier en "payé"
         */
    }
}