<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Application_View_Helper_CalendarDate
 *
 * @author fabrice
 */
class Application_View_Helper_CalendarDate
{
    public function calendarDate($dateString)
    {
        $date = new DateTime($dateString);
        return $date->format('Y-m-d');
    }
}