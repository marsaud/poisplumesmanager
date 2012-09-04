<?php

/**
 *
 */

/**
 * Description of CashRegister_View_Helper_ArticlePad
 *
 * @author fabrice
 */
class CashRegister_View_Helper_ArticlePad
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
            $promo = NULL;
            if (!empty($article->promos))
            {
                $promos = $article->promos;
                $promo = array_pop($promos);
            }

            /* @var $article Article */
            $articlePad .= '<div class="article button" ref="' . $article->reference . '" name="' . $article->name . '">'
                . PHP_EOL . $article->name
                . PHP_EOL . '<input type="hidden" class="qty" name="' . $article->reference . '" value="0">'
                . PHP_EOL . '<input type="hidden" class="promoid" name="promo_' . $article->reference
                . '" value="' . ($promo ? $promo->id : '')
                . '" ratiobuffer="' . ($promo ? $promo->ratio : '') . '">'
                    . '</div>' . PHP_EOL;
        }

        return $articlePad;
    }

}