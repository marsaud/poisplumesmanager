<?php

// Define path to application directory
defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

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

/*
 * Taxe 19.6 -> 20
 */

$select = $db->select()
        ->from(array('ol' => 'operationlines'), '*')
        ->joinInner(array('ct' => 'carttrailer'), 'ct.hash = ol.hash', array())
        ->where('ct.payment_date >= ?', '2014-01-01')
        ->where('ol.tax_id = ?', 6, Zend_Db::BIGINT_TYPE)
        ->where('ol.tax_ratio = ?', 19.60, Zend_Db::FLOAT_TYPE);

$query = $select->query();
$col = false;
while ($row = $query->fetch(Zend_Db::FETCH_ASSOC))
{
    if (!$col)
    {
        echo implode("\t", array_keys($row)) . PHP_EOL;
        $col = true;
    }
    echo implode("\t", $row) . PHP_EOL;
    
    $row['tax_ratio'] = 20;
//    $row['tax_id'] = 8;
    $row['raw_price'] = round(($row['final_price'] / 1.2), 2);
    $row['tax_amount'] = $row['final_price'] - $row['raw_price'];
    
    $where = array(
        'hash = ?' => $row['hash'],
        'reference = ?' => $row['reference']
    );
    
    $db->update('operationlines', $row, $where);
}

echo '========================================' . PHP_EOL;

$select = $db->select()
        ->from(array('ol' => 'operationlines'), '*')
        ->joinInner(array('ct' => 'carttrailer'), 'ct.hash = ol.hash', array())
        ->where('ct.payment_date >= ?', '2014-01-01')
        ->where('ol.tax_id = ?', 6, Zend_Db::BIGINT_TYPE)
        ->where('ol.tax_ratio = ?', 20, Zend_Db::FLOAT_TYPE);

$query = $select->query();

$col = false;
while ($row = $query->fetch(Zend_Db::FETCH_ASSOC))
{
    if (!$col)
    {
        echo implode("\t", array_keys($row)) . PHP_EOL;
        $col = true;
    }
    echo implode("\t", $row) . PHP_EOL;
}

echo '========================================' . PHP_EOL;
echo '========================================' . PHP_EOL;

/*
 * Taxe 7 -> 10
 */

$select = $db->select()
        ->from(array('ol' => 'operationlines'), '*')
        ->joinInner(array('ct' => 'carttrailer'), 'ct.hash = ol.hash', array())
        ->where('ct.payment_date >= ?', '2014-01-01')
        ->where('ol.tax_id = ?', 7, Zend_Db::BIGINT_TYPE)
        ->where('ol.tax_ratio = ?', 7, Zend_Db::FLOAT_TYPE);

$query = $select->query();
$col = false;
while ($row = $query->fetch(Zend_Db::FETCH_ASSOC))
{
    if (!$col)
    {
        echo implode("\t", array_keys($row)) . PHP_EOL;
        $col = true;
    }
    echo implode("\t", $row) . PHP_EOL;
    
    $row['tax_ratio'] = 10;
//    $row['tax_id'] = 8;
    $row['raw_price'] = round(($row['final_price'] / 1.1), 2);
    $row['tax_amount'] = $row['final_price'] - $row['raw_price'];
    
    $where = array(
        'hash = ?' => $row['hash'],
        'reference = ?' => $row['reference']
    );
    
    $db->update('operationlines', $row, $where);
}

echo '========================================' . PHP_EOL;

$select = $db->select()
        ->from(array('ol' => 'operationlines'), '*')
        ->joinInner(array('ct' => 'carttrailer'), 'ct.hash = ol.hash', array())
        ->where('ct.payment_date >= ?', '2014-01-01')
        ->where('ol.tax_id = ?', 7, Zend_Db::BIGINT_TYPE)
        ->where('ol.tax_ratio = ?', 10, Zend_Db::FLOAT_TYPE);

$query = $select->query();

$col = false;
while ($row = $query->fetch(Zend_Db::FETCH_ASSOC))
{
    if (!$col)
    {
        echo implode("\t", array_keys($row)) . PHP_EOL;
        $col = true;
    }
    echo implode("\t", $row) . PHP_EOL;
}

$db->rollback();