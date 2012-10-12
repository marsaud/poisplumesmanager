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
        $tableTrail = 'operationtrail';
        $bindTrail = array(
            'hash' => $hash,
            'total_raw_price' => $totalRawPrice,
            'total_tax' => $totalTax,
            'total_sale_price' => $totalSalePrice,
        );
        
        foreach ($payments as $payement)
        {
            /* @var $payement Payment */
            $bindTrail[$payement->name] = $payement->percieved - $payement->returned;
        }
        
        /**
         * @todo Créer les lignes d'articles qui détaillent l'opération
         */
        $bindsArticle = array();
        foreach ($soldArticles as $article)
        {
            /* @var $article Article */
            $bind = array(
                'hash' => $hash,
                'reference' => $article->reference,
                'quantity' => $article->quantity,
                'raw_price' => $article->getRawPrice(),
                'tax_amount' => $article->getTaxAmount(),
                'sale_price' => $article->getSalePrice(),
                'final_price' => $article->getPromotionPrice(),
                'tax_id' => $article->tax->id,
                'tax_ratio' => $article->tax->ratio,
                'promo_id' => $article->onePromo->id,
                'promo_ratio' => $article->onePromo->ratio
            );
            
            $bindsArticle[] = $bind;
        }
        
        /**
         * @todo Passer le panier en "payé"
         */
        $date = new DateTime();
        $dateString = $date->format('Y-m-d H:i:s');
        $this->_db->update('carttrail', array(
            'payed' => true,
            'payement_date' => $dateString
        ), "hash = '$hash'");
    }
}