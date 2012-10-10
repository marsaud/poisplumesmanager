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

    public function compute(array $soldArticles, &$totalRawPrice, &$totalTax, &$totalSalePrice)
    {
        foreach ($soldArticles as $soldArticle)
        {
            $quantity = $soldArticle->quantity;

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