<?php

/**
 *
 */

/**
 * Description of Admin_View_Helper_ArticleBox
 *
 * @author fabrice
 */
class Admin_View_Helper_ArticleBox
{

    /**
     *
     * @param string $name
     * @param Article[] $articles
     * @param string $label
     *
     * @return string
     */
    public function articleBox($name, array $articles, $label = NULL)
    {
        $label !== NULL
            || $label = $name;

        $articleBox = '<label for="' . $name . '">' . $label . '</label>'
            . PHP_EOL
            . '<select id="' . $name . '" name="' . $name . '">' . PHP_EOL;

        foreach ($articles as $article)
        {
            /* @var $article Article */
            $articleBox .= '<option value="' . $article->reference . '">'
                . $article->reference . ' - ' . $article->name . '</option>'
                . PHP_EOL;
        }

        $articleBox .= '</select>' . PHP_EOL;

        return $articleBox;
    }

}