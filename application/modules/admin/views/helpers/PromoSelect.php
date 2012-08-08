<?php

/**
 *
 */

/**
 * Description of Admin_View_Helper_PromotionSelect
 *
 * @author fabrice
 */
class Admin_View_Helper_PromoSelect
{

    /**
     *
     * @param string $name
     * @param Promotion[] $promoList
     * @param string $label
     *
     * @return string
     */
    public function promoSelect($name, array $promoList, $label = NULL)
    {
        $label !== NULL
            || $label = $name;

        /**
         * @todo Quelle valeur pour la catégorie par défaut ?
         */
        $promoSelect = '';

        foreach ($promoList as $promo)
        {
            /* @var $promo Promotion */
            $promoSelect .= '<option value="'
                . $promo->id
                . '">'
                . $promo->name
                . '</option>'
                . PHP_EOL;
        }

        $promoSelect = '<label for="' . $name . '">' . $label . '</label>'
            . PHP_EOL
            . '<select multiple="multiple" size="10" id="' . $name . '" name="' . $name . '[]">'
            . PHP_EOL
            . $promoSelect
            . PHP_EOL
            . '</select>'
            . PHP_EOL;

        return $promoSelect;
    }

}