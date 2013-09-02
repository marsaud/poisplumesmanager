<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Application_View_Helper_TrigCalendar
 *
 * @author fabrice
 */
class Application_View_Helper_TrigCalendar extends Zend_View_Helper_Abstract
{

    public function trigCalendar()
    {
        if (preg_match('/chrome/i', $_SERVER['HTTP_USER_AGENT']))
        {
            $output = '';
        }
        else
        {
            $this->view->headLink()->appendStylesheet($this->view->baseUrl('/css/calendar.css'));
            $output = '<script type="text/javascript" src="' . $this->view->baseUrl('/js/calendar.js') . '"></script>
<script type="text/javascript">
    <!--
    init_evenement();
    //-->
</script>';
        }

        return $output;
    }

}