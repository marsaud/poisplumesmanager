<?php
/**
 *
 */

/**
 * Description of Admin_View_Helper_PromotionSelect
 *
 * @author fabrice
 */
class Admin_View_Helper_PromoOptions
{

    /**
     *
     * @param Promotion[] $promoList
     *
     * @return string
     */
    public function promoOptions(array $promoList)
    {
        ob_start();

        foreach ($promoList as $promo) :
            ?>
            <option value="<?php echo $promo->id; ?>"><?php echo $promo->name . ' : ' . $promo->ratio; ?>%</option>
            <?php
        endforeach;

        return ob_get_clean();
    }

}
