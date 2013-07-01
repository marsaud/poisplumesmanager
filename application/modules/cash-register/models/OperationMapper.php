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
        $diff = abs(round($totalPaid - $totalSalePrice, 3));
        if ($diff != 0)
        {
            ob_start();
            var_dump($totalPaid, $totalSalePrice, $diff, $payments);
            throw new Exception('WRONG TOTAL CONSOLIDATION' . ob_get_clean());
        }

        try
        {
            /**
             * @todo Créer une ligne qui "résume" l'opération
             */
            $tableTrail = 'operationstrail';
            $bindTrail = array(
                'hash' => $hash,
                'total_raw_price' => $totalRawPrice, // @todo FAUX !! c'est un array !
                'total_tax' => $totalTax, // @todo FAUX !! c'est un array !
                'total_sale_price' => $totalSalePrice,
            );
            foreach ($payments as $payement)
            {
                /* @var $payement Payment */
                $bindTrail[$payement->reference] = $payement->percieved - $payement->returned;
            }
            $this->_db->insert($tableTrail, $bindTrail);

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
                    'quantity' => $article->soldQuantity,
                    'raw_price' => $article->getRawPrice(),
                    'tax_amount' => $article->getTaxAmount(),
                    'sale_price' => $article->getSalePrice(),
                    'final_price' => $article->getFinalPrice(),
                    'tax_id' => $article->tax->id,
                    'tax_ratio' => $article->tax->ratio,
                    'promo_id' => $article->onePromo ? $article->onePromo->id : null,
                    'promo_ratio' => $article->onePromo ? $article->onePromo->ratio : null
                );

                $bindsArticle[] = $bind;
            }
            $tableLines = 'operationlines';
            foreach ($bindsArticle as $bindLine)
            {
                $this->_db->insert($tableLines, $bindLine);
            }

            /**
             * @todo Passer le panier en "payé"
             */
            $date = new DateTime();
            $dateString = $date->format('Y-m-d H:i:s');
            $this->_db->update('carttrailer', array(
                'payed' => true,
                'payment_date' => $dateString
                    ), "hash = '$hash'");
        }
        catch (Exception $exc)
        {
            throw $exc;
        }
    }

}