<?php

/**
 *
 */

/**
 * Description of Admin_View_Helper_CategoryBox
 *
 * @author fabrice
 */
class Admin_View_Helper_CategoryBox
{

    /**
     *
     * @param string $name
     * @param Category[] $categoryTree
     * @param string $label
     * @param boolean $parentCategoriesOnly
     *
     * @return string
     */
    public function categoryBox($name, array $categoryTree, $label = NULL, $parentCategoriesOnly = false)
    {
        $label !== NULL
            || $label = $name;

        /**
         * @todo Quelle valeur pour la catégorie par défaut ?
         */
        $categoryBox = '<option value="" selected="selected"> - - </option>'
            . PHP_EOL;

        foreach ($categoryTree as $category)
        {
            /* @var $category Category */
            $categoryBox .= '<option value="'
                . $category->reference
                . '">'
                . $category->name
                . '</option>'
                . PHP_EOL;
            if (!$parentCategoriesOnly)
            {
                foreach ($category as $subCategory)
                {
                    /* @var $subCategory Category */
                    $categoryBox .= '<option value="'
                        . $subCategory->reference
                        . '">--> '
                        . $subCategory->name
                        . '</option>'
                        . PHP_EOL;
                }
            }
        }

        $categoryBox = '<label for="' . $name . '">' . $label . '</label>'
            . PHP_EOL
            . '<select id="' . $name . '" name="' . $name . '">'
            . PHP_EOL
            . $categoryBox
            . PHP_EOL
            . '</select>'
            . PHP_EOL;

        return $categoryBox;
    }

}