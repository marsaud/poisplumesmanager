<?php

/**
 *
 */

/**
 * Description of CashRegister_View_Helper_ArticlePad
 *
 * @author fabrice
 */
class CashRegister_View_Helper_ArticlePad extends Zend_View_Helper_Abstract
{

    /**
     *
     * @param Article[] $articles
     *
     * @return string
     */
    public function articlePad(array $articles)
    {
        $articlePad = '';

        foreach ($articles as $article)
        {
            /* @var $promo Promotion */
            $promo = array_pop($article->promos);

            /* @var $article Article */
            $articlePad .= '<div class="article button" ref="' . $article->reference . '" name="' . $article->name
                . '" ratiobuffer="' . ($promo ? $promo->ratio : '') . '" pricebuffer="' . $article->getSalePrice() . '">'
                . PHP_EOL
                . $article->name . '<br />' . ($promo ? $promo->ratio . '%' : '')
                . '<br />' . $this->view->currency($article->getSalePrice())
                . '<input type="hidden" class="qty" name="' . $article->reference . '" value="0">'
                . PHP_EOL
                . '<input type="hidden" class="promoid" name="promo_' . $article->reference
                . '" value="' . ($promo ? $promo->id : '')
                . '" ratiobuffer="' . ($promo ? $promo->ratio : '') . '">'
                . '</div>'
                . PHP_EOL;
        }

        return $articlePad;
    }

}