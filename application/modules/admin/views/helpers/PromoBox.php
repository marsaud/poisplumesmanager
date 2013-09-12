<?php

/**
 *
 */

/**
 * Description of Admin_View_Helper_PromoBox
 *
 * @author fabrice
 */
class Admin_View_Helper_PromoBox
{

    /**
     *
     * @param string $name
     * @param Promotion[] $promoList
     * @param string $label
     *
     * @return string
     */
    public function promoBox($name, array $promoList, $label = NULL)
    {
        $label !== NULL
                || $label = $name;

        $promoBox = '<label for="' . $name . '">' . $label . '</label>'
                . PHP_EOL
                . '<select id="' . $name
                . '" name="' . $name . '" class="form-control">' . PHP_EOL
                . '<option value="0">- -</option>' . PHP_EOL;
        foreach ($promoList as $promo)
        {
            /* @var $promo Promotion */
            $promoBox .= '<option value="' . $promo->id . '">'
                    . $promo->name . ' ' . $promo->ratio
                    . '%</option>'
                    . PHP_EOL;
        }

        $promoBox .= '</select>' . PHP_EOL;

        return $promoBox;
    }

}