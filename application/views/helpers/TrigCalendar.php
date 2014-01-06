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
        ob_start();

        if (!preg_match('/chrome/i', $_SERVER['HTTP_USER_AGENT']))
        {
            $this->view->headLink()->appendStylesheet($this->view->baseUrl('/css/calendar.css'));
            ?><script type="text/javascript" src="<?php echo $this->view->baseUrl('/js/calendar.js'); ?>"></script>
            <script type="text/javascript">
                <!--
                jQuery(document).ready(init_evenement);
            //-->
            </script><?php
        }

        return ob_get_clean();
    }

}
