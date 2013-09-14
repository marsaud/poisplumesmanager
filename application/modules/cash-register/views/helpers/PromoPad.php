<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CashRegister_View_Helper_PromoPad
 *
 * @author fabrice
 */
class CashRegister_View_Helper_PromoPad
{

    /**
     *
     * @param Promotion[] $promoList
     */
    public function promoPad(array $promoList)
    {
        $promoPad = '';

//        foreach ($promoList as $promo)
//        {
//            /* @var $promo Promotion */
//            $promoPad .= '<div class="promotion button" ref="' . $promo->id . '" name="' . $promo->name . '">'
//                . $promo->name
//                . '<input type="hidden" name="promo_" value="' . $promo->id . '"/>'
//                . '</div>' . PHP_EOL;
//        }

        foreach ($promoList as $promo)
        {
            /* @var $promo Promotion */
            $promoPad .= '<input type="button" class="button btn btn-info" promoid="' . $promo->id
                . '" promoratio="' . $promo->ratio
                . '" value="' . $promo->name . PHP_EOL . $promo->ratio
                . '%"/>' . PHP_EOL;
        }
        return $promoPad;
    }

}