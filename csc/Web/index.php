<?php

namespace SCL\Web;

error_reporting(E_ALL);

define('SCL_SAFETY_CONST', true);

define('SCL_DS', DIRECTORY_SEPARATOR);
define('SCL_ROOT_DIR', dirname(__DIR__) . SCL_DS);

require_once SCL_ROOT_DIR . 'Config' . SCL_DS . 'settings.php';

require_once SCL_LIB_DIR . 'Autoloader.php';
$loader = new \SCL\lib\Autoloader();
$loader->register();

// var_dump($_POST);
// var_dump($_FILES);
// exit;

$core = new \SCL\Controller\Core();

$result = $core->init();
