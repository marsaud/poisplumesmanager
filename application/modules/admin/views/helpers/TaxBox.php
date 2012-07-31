<?php

/**
 *
 */

/**
 * Description of Admin_View_Helper_TaxBox
 *
 * @author fabrice
 */
class Admin_View_Helper_TaxBox
{

    /**
     *
     * @param string $name
     * @param Tax[] $taxList
     * @param string $label
     *
     * @return string
     */
    public function taxBox($name, array $taxList, $label = NULL)
    {
        $label !== NULL
            || $label = $name;

        $taxBox = '<label for="' . $name . '">' . $label . '<label>'
            . PHP_EOL
            . '<select id="' . $name . '" name="' . $name . '">';
        foreach ($taxList as $tax)
        {
            /* @var $tax Tax */
            $taxBox .= '<option value="' . $tax->id . '">'
                . $tax->name . ' - ' . $tax->ratio
                . '%</option>'
                . PHP_EOL;
        }

        $taxBox .= '</select>' . PHP_EOL;

        return $taxBox;
    }

}