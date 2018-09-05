<?php

namespace SCL;

error_reporting(0);

define("SCL_SAFETY_CONST", true);

define("SCL_DS", DIRECTORY_SEPARATOR);
define("SCL_ROOT_DIR", dirname(__DIR__) . SCL_DS . "csc" . SCL_DS);

require_once SCL_ROOT_DIR . "Config" . SCL_DS . "settings.php";

require_once SCL_LIB_DIR . "Autoloader.php";
$loader = new \SCL\lib\Autoloader();
$loader->register();

$core = new \SCL\Controller\Core();

$result = $core->init();
