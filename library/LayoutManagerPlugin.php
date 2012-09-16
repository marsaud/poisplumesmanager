<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LayoutManagerPlugin
 *
 * @author MAKRIS
 */
class LayoutManagerPlugin extends Zend_Controller_Plugin_Abstract
{

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $moduleName = $request->getModuleName();

        if ($moduleName != 'default')
        {
            Zend_Layout::getMvcInstance()->setLayoutPath(implode(DIRECTORY_SEPARATOR, array(
                        APPLICATION_PATH,
                        'modules',
                        $moduleName,
                        'layouts',
                        'scripts'
                    )));
        }
    }

}
