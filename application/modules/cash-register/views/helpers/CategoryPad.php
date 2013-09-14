<?php

/**
 *
 */

/**
 * Description of CashRegister_View_Helper_CategoryPad
 *
 * @author fabrice
 */
class CashRegister_View_Helper_CategoryPad
{

    /**
     *
     * @param Category[] $categoryList
     *
     * @return string
     */
    public function categoryPad(array $categoryList)
    {
        $categoryPad = '';

        foreach ($categoryList as $category)
        {
            /* @var $category Category */
            $categoryPad .= '<div class="category button btn btn-primary" ref="' . $category->reference . '">'
                . $category->name . '</div>' . PHP_EOL;
        }

        return $categoryPad;
    }

}