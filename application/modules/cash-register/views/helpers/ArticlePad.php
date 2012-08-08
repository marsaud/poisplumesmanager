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
            $articlePad .= '<input type="button" value="'
                . $article->reference . '"/>' . PHP_EOL;
        }

        return $articlePad;
    }
}