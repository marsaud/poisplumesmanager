<?php

/**
 * 
 */

/**
 * Description of Admin_View_Helper_ProviderBox
 *
 * @deprecated since version >1.0
 * 
 * @author fabrice
 */
class Admin_View_Helper_ProviderBox
{

    /**
     *
     * @param string $name
     * @param array $providerList
     * @param string $prefix
     * @param string $label
     *
     * @return string
     */
    public function providerBox($name, array $providerList, $label = NULL)
    {
        $label !== NULL
                || $label = $name;

        $providerBox = '<label for="' . $name . '">' . $label . '</label>'
                . PHP_EOL
                . '<select id="' . $name
                . '" name="' . $name . '" class="form-control">'
                . PHP_EOL
                . '<option value="0">- -</option>'
                . PHP_EOL;

        foreach ($providerList as $provider)
        {
            /* @var $provider Provider */
            $providerBox .= '<option value="' . $provider->id . '">'
                    . $provider->name
                    . '</option>';
        }

        $providerBox .= '</select>' . PHP_EOL;

        return $providerBox;
    }

}