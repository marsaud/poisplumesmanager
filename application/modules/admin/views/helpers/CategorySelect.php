<?php

/**
 *
 */

/**
 * Description of Admin_View_Helper_CategorySelect
 *
 * @deprecated since version >1.0
 * 
 * @author fabrice
 */
class Admin_View_Helper_CategorySelect
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
    public function categorySelect($name, array $categoryTree, $label = NULL, $parentCategoriesOnly = false)
    {
        $label !== NULL
                || $label = $name;

        /**
         * @todo Quelle valeur pour la catégorie par défaut ?
         */
        $categorySelect = '';

        foreach ($categoryTree as $category)
        {
            /* @var $category Category */
            $categorySelect .= '<option value="'
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
                    $categorySelect .= '<option value="'
                            . $subCategory->reference
                            . '">&gt; '
                            . $subCategory->name
                            . '</option>'
                            . PHP_EOL;
                }
            }
        }

        $categorySelect = '<label for="' . $name . '">' . $label . '</label>'
                . PHP_EOL
                . '<select multiple="multiple" size="10" id="' . $name
                . '" name="' . $name . '[]" class="form-control">'
                . PHP_EOL
                . $categorySelect
                . PHP_EOL
                . '</select>'
                . PHP_EOL;

        return $categorySelect;
    }

}