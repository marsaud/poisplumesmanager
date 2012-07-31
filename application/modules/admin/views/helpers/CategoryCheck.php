<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
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
    public function categoryCheck(array $categoryList, $legend, $prefix = '')
    {

        $categoryCheck = '<fieldset>'
            . PHP_EOL
            . '<legend>' . $legend . '</legend>'
            . PHP_EOL;

        foreach ($categoryList as $category)
        {
            /* @var $category Category */
            $categoryCheck .= '<label for="' . $prefix . $category->reference . '">'
                . $category->name
                . '</label>'
                . PHP_EOL
                . '<input id="' . $prefix . $category->reference
                . '" type="checkbox" name="' . $prefix . $category->reference
                . '" value="on" /><br/>'
                . PHP_EOL;
        }

        $categoryCheck .= '</fieldset>' . PHP_EOL;

        return $categoryCheck;
    }

}