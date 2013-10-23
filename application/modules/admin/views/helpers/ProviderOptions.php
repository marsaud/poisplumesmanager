<?php
/**
 * 
 */

/**
 * Description of Admin_View_Helper_ProviderOptions
 *
 * @author fabrice
 */
class Admin_View_Helper_ProviderOptions
{

    /**
     *
     * @param Provider[] $providerList
     *
     * @return string
     */
    public function providerOptions(array $providerList)
    {
        ob_start();

        foreach ($providerList as $provider):
            ?>
            <option value="<?php echo $provider->id; ?>"><?php echo $provider->name; ?></option>
            <?php
        endforeach;

        return ob_get_clean();
    }

}
