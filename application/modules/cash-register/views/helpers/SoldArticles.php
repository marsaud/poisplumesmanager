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
     * @param SoldArticle[] $soldArticles
     */
    public function soldArticles(array $soldArticles)
    {
        $output = '';

        foreach ($soldArticles as $soldArticle)
        {
            /* @var $soldArticle SoldArticle */
            $a = $soldArticle->article;
            $output .= $a->name . "\n"
                    . $a->reference . "\t"
                    . $soldArticle->quantity . " x \t"
                    . $this->view->currency($a->getPromotionPrice()) . "\t"
                    . $this->view->currency(
                            $soldArticle->quantity * $a->getPromotionPrice()
                    ) . "\n";
        }

        return $output;
    }

}

?>
