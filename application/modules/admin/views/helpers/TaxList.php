<?php

/**
 *
 */

/**
 * Description of Admin_View_Helper_TaxList
 *
 * @author fabrice
 */
class Admin_View_Helper_TaxList
{

    /**
     *
     * @param Tax[] $taxList
     * @param string $caption
     *
     * @return string
     */
    public function taxList(array $taxes, $caption)
    {
        $taxList = '<table class="table table-striped">' . PHP_EOL
                . '<caption>' . $caption . '</caption>' . PHP_EOL
                . '<tr><th>Nom</th><th>Ratio</th><th>Description</th></tr>' . PHP_EOL;

        foreach ($taxes as $tax)
        {
            /* @var $tax Tax */
            $taxList .= '<tr><td>' . $tax->name . '</td><td>' . $tax->ratio
                    . '%</td><td>' . $tax->description . '</td></tr>' . PHP_EOL;
        }

        $taxList .= '</table>' . PHP_EOL;

        return $taxList;
    }

}