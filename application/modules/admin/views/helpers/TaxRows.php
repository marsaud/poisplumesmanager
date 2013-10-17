<?php
/**
 *
 */

/**
 * Description of Admin_View_Helper_TaxRows
 *
 * @author fabrice
 */
class Admin_View_Helper_TaxRows
{

    /**
     *
     * @param Tax[] $taxes
     *
     * @return string
     */
    public function taxRows(array $taxes)
    {
        ob_start();

        foreach ($taxes as $tax):
            ?>
            <tr>
                <td><a id="<?php echo $tax->id; ?>"><?php echo $tax->name; ?></a></td>
                <td><?php echo $tax->ratio; ?>%</td>
                <td><?php echo $tax->description; ?></td>
            </tr>
            <?php
        endforeach;

        return ob_get_clean();
    }

}