<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

/**
 * Description of CategoryTest
 *
 * @author fabrice
 */
class CategoryTest extends PHPUnit_Extensions_SeleniumTestCase
{

    function setUp()
    {
        $this->setBrowser("googlechrome C:\Program Files (x86)\Google\Chrome\Application\chrome.exe");
        $this->setBrowserUrl("http://poisplumesmanager.localhost");
    }

    function testAccess()
    {
        $this->open('/');
        $this->assertElementPresent('css=a[href="/admin"]');
        $this->click('css=a[href="/admin"]');
        $this->waitForPageToLoad();
        $this->assertElementPresent('css=a[href="/admin/category"]');
        $this->click('css=a[href="/admin/category"]');
        $this->waitForPageToLoad();

        $this->assertElementPresent('identifier=createcategoryform');
        $this->assertElementPresent('identifier=updatecategoryform');
    }

    /**
     * @depends testAccess
     */
    function testCreate()
    {
        $testTokens = array(
            0 => uniqid(),
            1 => uniqid(),
            2 => uniqid()
        );

        $this->open('/admin/category');

        foreach ($testTokens as $testToken)
        {
            $this->type('identifier=categoryref', 'refC' . $testToken);
            $this->type('identifier=categoryname', 'nameC' . $testToken);
            $this->type('identifier=categorydesc', 'descC' . $testToken);
            $this->submit('identifier=createcategoryform');
            $this->waitForPageToLoad();

            $this->assertElementContainsText('identifier=categorylist', 'refC' . $testToken);
            $this->assertElementContainsText('identifier=categorylist', 'nameC' . $testToken);
            $this->assertElementContainsText('identifier=categorylist', 'descC' . $testToken);
        }

        return $testTokens;
    }

    /**
     * @depends testCreate
     */
    function testUpdate($testTokens)
    {
        $this->open('/admin/category');

        foreach ($testTokens as $testToken)
        {
            $this->select('identifier=modcategoryref', 'value=refC' . $testToken);
            usleep(500000);
            $this->assertElementValueEquals('identifier=modcategoryname', 'nameC' . $testToken);
            $this->assertElementValueEquals('identifier=modcategorydesc', 'descC' . $testToken);
            /* @todo Try to check the parent multiselect */

            $this->type('identifier=modcategoryname', 'nameM' . $testToken);
            $this->type('identifier=modcategorydesc', 'descM' . $testToken);
            $this->submit('identifier=updatecategoryform');
            $this->waitForPageToLoad();

            $this->select('identifier=modcategoryref', 'value=refC' . $testToken);
            usleep(500000);
            $this->assertElementValueEquals('identifier=modcategoryname', 'nameM' . $testToken);
            $this->assertElementValueEquals('identifier=modcategorydesc', 'descM' . $testToken);
            /* @todo Try to check the parent multiselect */
        }

        return $testTokens;
    }

    /**
     * @depends testUpdate
     */
    function testParentCategory($testTokens)
    {
        $this->open('admin/category');
        foreach ($testTokens as $testToken)
        {
            $this->type('identifier=categoryref', 'refP' . $testToken);
            $this->type('identifier=categoryname', 'nameP' . $testToken);
            $this->type('identifier=categorydesc', 'descP' . $testToken);
            $this->submit('identifier=createcategoryform');
            $this->waitForPageToLoad();

            $this->assertElementContainsText('identifier=categorylist', 'refP' . $testToken);
            $this->assertElementContainsText('identifier=categorylist', 'nameP' . $testToken);
            $this->assertElementContainsText('identifier=categorylist', 'descP' . $testToken);
            
            $this->type('identifier=categoryref', 'refS' . $testToken);
            $this->type('identifier=categoryname', 'nameS' . $testToken);
            $this->type('identifier=categorydesc', 'descS' . $testToken);
            $this->select('identifier=parentcategory', 'value=refP' . $testToken);
            $this->submit('identifier=createcategoryform');
            $this->waitForPageToLoad();

            $this->assertElementContainsText('identifier=categorylist', 'refS' . $testToken);
            $this->assertElementContainsText('identifier=categorylist', 'nameS' . $testToken);
            $this->assertElementContainsText('identifier=categorylist', 'descS' . $testToken);
            
        }
    }

}