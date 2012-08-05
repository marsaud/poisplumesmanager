<?php

/**
 *
 */

/**
 * Description of Admin_View_Helper_CategoryCheck
 *
 * @author fabrice
 */
class Admin_View_Helper_CategoryCheck
{

    /**
     *
     * @param Category[] $categoryList
     * @param string $legend
     * @param string $prefix
     *
     * @return string
     */
    public function categoryCheck(array $categoryList, $id, $legend, $prefix = '')
    {

        $categoryCheck = '<fieldset id="' . $id . '">' . PHP_EOL
            . '<legend>' . $legend . '</legend>' . PHP_EOL;

        foreach ($categoryList as $category)
        {
            $categoryCheck .= $this->_renderCategoryCheckBox(
                $category, $prefix
            );
            foreach ($category as $subCategory)
            {
                $categoryCheck .= $this->_renderCategoryCheckBox(
                    $subCategory, $prefix
                );
            }
        }

        $categoryCheck .= '</fieldset>' . PHP_EOL;

        return $categoryCheck;
    }

    protected function _renderCategoryCheckBox(Category $category, $prefix)
    {
        return '<label for="' . $prefix . $category->reference . '">'
            . $category->name
            . '</label>'
            . PHP_EOL
            . '<input id="' . $prefix . $category->reference
            . '" type="checkbox" name="' . $prefix . $category->reference
            . '" value="on" /><br/>'
            . PHP_EOL;
    }

}