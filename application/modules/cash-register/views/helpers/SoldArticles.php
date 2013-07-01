<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CashRegister_View_Helper_SoldArticles
 *
 * @author MAKRIS
 */
class CashRegister_View_Helper_SoldArticles extends Zend_View_Helper_Abstract
{

    /**
     * 
     * @param Article[] $soldArticles
     */
    public function soldArticles(array $soldArticles)
    {
        $output = '';

        foreach ($soldArticles as $soldArticle)
        {
            /* @var $soldArticle Article */
            $output .= $soldArticle->name . "\n"
                    . $soldArticle->reference . "\t"
                    . $soldArticle->soldQuantity . " x \t"
                    . $this->view->currency($soldArticle->getFinalPrice()) . "\t"
                    . $this->view->currency(
                            $soldArticle->soldQuantity * $soldArticle->getFinalPrice()
                    ) . "\n";
        }

        return $output;
    }

}

?>
