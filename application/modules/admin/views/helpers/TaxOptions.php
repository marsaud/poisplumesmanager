<?php
/**
 *
 */

/**
 * Description of Admin_View_Helper_TaxOptions
 *
 * @author fabrice
 */
class Admin_View_Helper_TaxOptions
{

    /**
     *
     * @param Tax[] $taxes
     *
     * @return string
     */
    public function taxOptions(array $taxes)
    {
        ob_start();

        foreach ($taxes as $tax):
            ?>
            <option value="<?php echo $tax->id; ?>"><?php echo $tax->name . ' : ' . $tax->ratio; ?>%</option>
            <?php
        endforeach;

        return ob_get_clean();
    }

}