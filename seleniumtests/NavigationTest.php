<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

/**
 * Description of newSeleneseTest
 *
 * @author fabrice
 */
class NavigationTest extends PHPUnit_Extensions_SeleniumTestCase
{

    function setUp()
    {
        $this->setBrowser("googlechrome C:\Program Files (x86)\Google\Chrome\Application\chrome.exe");
        $this->setBrowserUrl("http://poisplumesmanager.localhost/");
    }

    function testHome()
    {
        $this->open('/');
        $this->assertTitle('CAISSE');
        $this->assertElementPresent('identifier=mainmenu');
    }

    /**
     * @dataProvider navbarProvider
     */
    function testNavbar($menuName, $links)
    {
        $this->open('/');

        $this->assertElementPresent('identifier=' . $menuName . 'menu');

        foreach ($links as $link)
        {
            $this->assertElementPresent('css=a[href="/' . $link . '"]');
        }
    }

    function navbarProvider()
    {
        $data = array();

        $data['main'] = array(
            'main',
            array(
                // 'admin',
                'admin/category',
                'admin/article',
                'admin/promotion',
                'admin/provider',
                'admin/tax',
                // 'stock',
                'cash-register',
                'report/index/detail',
                'report/index/week',
                'report/index/month',
                'report/index/cart',
                'report/index/margin',
                'purchase/index/manage',
                'dash-board/index/index'
            )
        );

        return $data;
    }

    /**
     * @dataProvider subNavbarProvider
     * 
     * @param string $menuName
     * @param string[] $links
     */
    function testSubNavbar($menuName, $links)
    {
        $this->open('/');

        $this->assertElementPresent('css=a[href="/' . $menuName . '"]');
        $this->click('css=a[href="/' . $menuName . '"]');
        $this->waitForPageToLoad();

        $this->assertElementPresent('identifier=' . $menuName . 'menu');

        foreach ($links as $link)
        {
            $this->assertElementPresent('css=a[href="/' . $menuName . '/' . $link . '"]');
        }
    }

    function subNavbarProvider()
    {
        $data = array();

        $data['admin'] = array(
            'admin',
            array(
                'category',
                'provider',
                'tax',
                'promotion',
                'article'
            )
        );
        /*
          $data['stock'] = array(
          'stock',
          array(
          )
          );

          $data['cash-register'] = array(
          'cash-register',
          array(
          )
          );
         */
        $data['report'] = array(
            'report',
            array(
                'index/detail',
                'index/week',
                'index/month',
                'index/cart',
                'index/margin'
            )
        );

        $data['purchase'] = array(
            'purchase',
            array(
                'index/manage'
            )
        );

        return $data;
    }

}