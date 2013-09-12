<?php

/**
 *
 */

/**
 * Description of Admin_View_Helper_CategoryList
 *
 * @author fabrice
 */
class Admin_View_Helper_CategoryList
{

    public function categoryList(array $categoryTree, $caption = NULL)
    {
        $caption !== NULL
                || $caption = 'Liste des catégories';

        $categoryList = '<table class="table table-striped">'
                . PHP_EOL
                . '<caption>' . $caption . '</caption>'
                . PHP_EOL
                . '<tr><th colspan="2">Catégorie</th>'
                . '<th>Nom</th><th>Description</th></tr>';

        foreach ($categoryTree as $category)
        {
            /* @var $category Category */
            $categoryList .= '<tr><td>'
                    . $category->reference
                    . '</td><td></td><td>'
                    . $category->name
                    . '</td><td>'
                    . $category->description
                    . '</td></tr>'
                    . PHP_EOL;
            foreach ($category as $subCategory)
            {
                /* @var $subCategory Category */
                $categoryList .= '<tr><td></td><td>'
                        . $subCategory->reference
                        . '</td><td>'
                        . $subCategory->name
                        . '</td><td>'
                        . $subCategory->description
                        . '</td></tr>'
                        . PHP_EOL;
            }
        }

        $categoryList .= '</table>' . PHP_EOL;

        return $categoryList;
    }

}