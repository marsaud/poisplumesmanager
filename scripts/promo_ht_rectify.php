<?php

// Define path to application directory
defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// die(APPLICATION_ENV);

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
        ->from('operationlines')
        ->where('promo_id is not null');

$query = $select->query();

while ($row = $query->fetch(Zend_Db::FETCH_ASSOC))
{
    echo implode("\t", $row) . PHP_EOL;
    
    $promoRawPrice = $row['raw_price'] * ((100 + $row['promo_ratio']) / 100);
    $promoCurrencyPrice = round(100 * $promoRawPrice) / 100;

    $one = $db->update('operationlines', array(
        'raw_price' => $promoCurrencyPrice
            ), array(
        "hash = '" . $row['hash'] . "'",
        "reference = '" . $row['reference'] . "'"
            ));

    if ($one != 1)
    {
        throw new Exception();
    }
}

// $db->commit();

echo '========================================' . PHP_EOL;

$query = $select->query();

while ($row = $query->fetch(Zend_Db::FETCH_ASSOC))
{
    echo implode("\t", $row) . PHP_EOL;
}

$db->rollback();