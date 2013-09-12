<?php

/**
 *
 */

/**
 * Description of Admin_View_Helper_ArticleList
 *
 * @author fabrice
 */
class Admin_View_Helper_ArticleList extends Zend_View_Helper_Abstract
{

    /**
     *
     * @param Article[] $articles
     * @param string $caption
     *
     * @return string
     */
    public function articleList(array $articles, $caption)
    {
        $articleList = '<table class="table table-striped">' . PHP_EOL
            . '<caption>' . $caption . '</caption>' . PHP_EOL
            . '<tr><th>Référence</th><th>Nom</th><th>Description</th>'
            . '<th>Catégories</th><th>Prix HT</th><th>TVA</th><th>Promos</th>'
            . '<th>Prix TTC</th><th>Fournisseur</th></tr>';
        foreach ($articles as $article)
        {
            /* @var $article Article */
            $categoryList = '';
            if (!empty($article->categories))
            {
                foreach ($article->categories as $category)
                {
                    /* @var $category Category */
                    $categoryList .= '* ' . $category->name . '<br/>';
                }
            }

            $promoList = '';
            if (count($article->promos) > 0)
            {
                foreach ($article->promos as $promo)
                {
                    /* @var $promo Promotion */
                    $promoList .= '* ' . $promo->name . ' : ' . $promo->ratio . '%<br />';
                }
            }

            $articleList .= '<tr><td>' . $article->reference . '</td><td>'
                . $article->name . '</td><td>'
                . $article->description . '</td><td>'
                . $categoryList . '</td><td>'
                . $this->view->currency($article->getSalePrice()) . '</td><td>'
                . $article->tax->ratio . '%</td><td>'
                . $promoList . '</td><td>'
                . $this->view->currency($article->getFinalPrice())
                . '</td><td>'
                . ($article->provider !== NULL ? $article->provider->name : '')
                . '</td></tr>' . PHP_EOL;
        }

        $articleList .= '</table>' . PHP_EOL;

        return $articleList;
    }

}