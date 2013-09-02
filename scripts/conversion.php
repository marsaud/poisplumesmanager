<?php

// Define path to application directory
defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'staging'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
            realpath(APPLICATION_PATH . '/../library')
        )));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
                APPLICATION_ENV,
                APPLICATION_PATH . '/configs/application.ini'
);

$application->getAutoloader()->setFallbackAutoloader(true);
$application->bootstrap();

/* @var $db Zend_Db_Adapter_Pdo_Abstract */
$db = $application->getBootstrap()->getResource('multidb')->getDb('ppmdb');
$db->beginTransaction();

$select = $db->select()
        ->from(array('ol' => 'operationlines'), '*')
        ->where('reference in (?)', array(
    'ACA', 'ACS', 'SAA', 'SAE', 'SJO', 'SMV'
        ));

//$query = $select->query();
//while ($row = $query->fetch(Zend_Db::FETCH_ASSOC))
//{
//    echo implode("\t", $row) . PHP_EOL;
//    
//    $row['tax_ratio'] = 5.50;
//    $row['tax_id'] = 8;
//    $row['raw_price'] = round(($row['sale_price'] / 1.055), 2);
//    $row['tax_amount'] = $row['sale_price'] - $row['raw_price'];
//    
//    $where = array(
//        'hash = ?' => $row['hash'],
//        'reference = ?' => $row['reference']
//    );
//    
//    $db->update('operationlines', $row, $where);
//}

echo '========================================' . PHP_EOL;

$query = $select->query();

while ($row = $query->fetch(Zend_Db::FETCH_ASSOC))
{
    echo implode("\t", $row) . PHP_EOL;
}

$db->rollback();