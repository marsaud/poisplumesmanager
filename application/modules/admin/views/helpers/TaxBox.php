<?php

/**
 *
 */

/**
 * Description of Admin_View_Helper_TaxBox
 * 
 * @deprecated since version >1.0
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

        $taxBox = '<label for="' . $name . '">' . $label . '</label>'
                . PHP_EOL
                . '<select id="' . $name
                . '" name="' . $name . '" class="form-control" required>'
                . PHP_EOL
                . '<option></option>' . PHP_EOL;
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