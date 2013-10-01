<?php

/**
 *
 */

/**
 * Description of Admin_View_Helper_ProviderList
 *
 * @author fabrice
 */
class Admin_View_Helper_ProviderList
{

    /**
     *
     * @param Provider[] $providers
     * @param string $caption
     *
     * @return string
     */
    public function providerList(array $providers, $caption)
    {
        $providerList = '<table class="table table-striped">' . PHP_EOL
                . '<caption>' . $caption . '</caption>'
                . '<tr><th>Nom</th><th>Informations et coordonnées</th>'
                . '<th>Commentaires</th></tr>' . PHP_EOL;

        foreach ($providers as $provider)
        {
            /* @var $provider Provider */
            $providerList .= '<tr><td>' . $provider->name . '</td>'
                    . '<td>' . $provider->info . '</td>'
                    . '<td>' . $provider->comment . '</td>'
                    . '</tr>' . PHP_EOL;
        }

        $providerList .= '</table>' . PHP_EOL;

        return $providerList;
    }

}