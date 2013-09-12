<?php

/**
 *
 */

/**
 * Description of Admin_View_Helper_PromoList
 *
 * @author fabrice
 */
class Admin_View_Helper_PromoList
{

    /**
     *
     * @param Promotion[] $promoList
     * @param string $caption
     *
     * @return string
     */
    public function promoList(array $promotions, $caption)
    {
        $promoList = '<table class="table table-striped">' . PHP_EOL
                . '<caption>' . $caption . '</caption>' . PHP_EOL
                . '<tr><th>Nom</th><th>Ratio</th><th>Description</th></tr>' . PHP_EOL;

        foreach ($promotions as $promo)
        {
            /* @var $promo Promotion */
            $promoList .= '<tr><td>' . $promo->name . '</td><td>' . $promo->ratio
                    . '%</td><td>' . $promo->description . '</td></tr>' . PHP_EOL;
        }

        $promoList .= '</table>' . PHP_EOL;

        return $promoList;
    }

}