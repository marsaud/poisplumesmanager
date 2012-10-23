<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OperationManager
 *
 * @author fabrice
 */
class OperationManager
{

    /**
     * 
     * @param Article[] $soldArticles
     * @param float $totalRawPrice
     * @param float $totalTax
     * @param float $totalSalePrice
     */
    public function compute(array $soldArticles, &$totalRawPrice, &$totalTax, &$totalSalePrice)
    {
        /* @var $soldArticle Article */
        foreach ($soldArticles as $soldArticle)
        {
            $quantity = $soldArticle->soldQuantity;

            isset($totalRawPrice[$soldArticle->tax->ratio])
                    || $totalRawPrice[$soldArticle->tax->ratio] = 0;
            isset($totalTax[$soldArticle->tax->ratio])
                    || $totalTax[$soldArticle->tax->ratio] = 0;

            $totalRawPrice[$soldArticle->tax->ratio] +=
                    $quantity * $soldArticle->getRawPrice();
            $totalSalePrice +=
                    $quantity * $soldArticle->getPromotionPrice();
            $totalTax[$soldArticle->tax->ratio] +=
                    $quantity * $soldArticle->getTaxAmount();
        }
    }

}