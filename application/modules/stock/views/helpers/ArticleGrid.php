<?php

/**
 *
 */

/**
 * Description of Stock_View_Helper_ArticleGrid
 *
 * @author fabrice
 */
class Stock_View_Helper_ArticleGrid
{

    /**
     *
     * @param Article[] $articleList
     */
    public function articleGrid(array $articleList)
    {

        $articleGrid = '<table>' . PHP_EOL . '<tr>' . PHP_EOL
                . '<th>Référence</th><th>Nom</th><th>Description</th>'
                . '<th>Quantité</th><th>Modifier</th>'
                . PHP_EOL . '</tr>' . PHP_EOL;

        foreach ($articleList as $article)
        {
            /* @var $article Article */
            $articleGrid .= '<tr>' . PHP_EOL
                    . '<td>' . $article->reference . '</td><td>' . $article->name
                    . '</td><td>' . $article->description . '</td><td id="d_' . $article->reference . '">'
                    . $article->stockedQuantity . (!empty($article->unit) ? (' ' . $article->unit) : '')
                    . '</td><td class="eventcell" id="' . $article->reference . '"><input type="text" id="q_'
                    . $article->reference . '" name="q_'
                    . $article->reference . '"/><input type="button" id="v_'
                    . $article->reference
                    . '" value="Ok"/><br /><textarea name="c_' . $article->reference . '" id="c_' . $article->reference . '">(r.a.s.)</textarea></td>'
                    . PHP_EOL . '</tr>';
        }

        $articleGrid .= '</table>' . PHP_EOL;

        return $articleGrid;
    }

}