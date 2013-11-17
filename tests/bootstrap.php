<?php

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'testing'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

$paths = array(
    APPLICATION_PATH . '/models',
    APPLICATION_PATH . '/modules/purchase/models',
    APPLICATION_PATH . '/modules/report/models',
    APPLICATION_PATH . '/modules/cash-register/models',
    APPLICATION_PATH . '/modules/stock/models',
    APPLICATION_PATH . '/../library'
);

foreach ($paths as $directory)
{
//    echo $directory . PHP_EOL;
    $dir = opendir($directory);
//    var_dump($dir);

    while (false !== ($file = readdir($dir)))
    {
        if (preg_match('/\.php$/', $file))
        {
//            echo $directory . '/' . $file . PHP_EOL;
            require_once $directory . '/' . $file;
        }
    }

    closedir($dir);
}

function _getDb()
{
    $db = new Zend_Db_Adapter_Pdo_Mysql(array(
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname' => 'poisplumesmanagerqual'
    ));
    
    return $db;
    
}