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
            /* @var $article Article */
            $articlePad .= '<div class="article button" ref="' . $article->reference . '" name="' . $article->name . '">'
                . $article->name . '</div>' . PHP_EOL;
        }

        return $articlePad;
    }

}