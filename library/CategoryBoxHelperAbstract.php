<?php

/**
 *
 */

/**
 * Description of CategoryBox
 * 
 * @deprecated since version >1.0
 *
 * @author fabrice
 */
abstract class CategoryBoxHelperAbstract
{

    /**
     *
     * @param string $name
     * @param Category[] $categoryTree
     * @param string $label
     * @param boolean $parentCategoriesOnly
     * @param string $selectedCategory
     * @param boolean $required
     *
     * @return string
     */
    public function categoryBox(
    $name, array $categoryTree, $label = NULL
    , $parentCategoriesOnly = false, $selectedCategory = NULL, $required = false
    )
    {
        $label !== NULL || $label = $name;

        $categoryBox = '<option'
                . ($selectedCategory === NULL ? ' selected="selected"' : '')
                . '></option>'
                . PHP_EOL;

        foreach ($categoryTree as $category)
        {
            /* @var $category Category */
            $categoryBox .= '<option value="' . $category->reference . '"'
                    . ($selectedCategory === $category->reference ?
                            ' selected="selected"' : '')
                    . '>'
                    . $category->name
                    . '</option>'
                    . PHP_EOL;
            if (!$parentCategoriesOnly)
            {
                foreach ($category as $subCategory)
                {
                    /* @var $subCategory Category */
                    $categoryBox .= '<option value="' . $subCategory->reference
                            . '"'
                            . ($selectedCategory === $subCategory->reference ?
                                    ' selected="selected"' : '')
                            . '>&gt; '
                            . $subCategory->name
                            . '</option>'
                            . PHP_EOL;
                }
            }
        }

        $categoryBox = '<label for="' . $name . '">' . $label . '</label>'
                . PHP_EOL
                . '<select id="' . $name
                . '" name="' . $name . '" class="form-control"' . ($required ? ' required' : '') . '>'
                . PHP_EOL
                . $categoryBox
                . PHP_EOL
                . '</select>'
                . PHP_EOL;

        return $categoryBox;
    }

}