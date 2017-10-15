<?php

defined('SCL_SAFETY_CONST') or die;

define("SCL_CLASSES_DIR",    SCL_ROOT_DIR . "Classes"    . SCL_DS);
define("SCL_CONFIG_DIR",     SCL_ROOT_DIR . "Config"     . SCL_DS);
define("SCL_CONTROLLER_DIR", SCL_ROOT_DIR . "Controller" . SCL_DS);
define("SCL_LIB_DIR",        SCL_ROOT_DIR . "Lib"        . SCL_DS);
define("SCL_LOGS_DIR",       SCL_ROOT_DIR . "Logs"       . SCL_DS);
define("SCL_MODEL_DIR",      SCL_ROOT_DIR . "Model"      . SCL_DS);
define("SCL_WEB_DIR",        SCL_ROOT_DIR . "Web"        . SCL_DS);
define("SCL_PAGES_DIR",      SCL_ROOT_DIR . "View" . SCL_DS . "Pages" . SCL_DS);
define("SCL_PARTS_DIR",      SCL_ROOT_DIR . "View" . SCL_DS . "Parts" . SCL_DS);

define('SCL_TIME_FORMAT', 'Y-m-d H:i:s');
define('SCL_BR', "\n");

// scheme:[//[user:password@]host[:port]][/]path[?query][#fragment]

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

define('SCL_URL_SCHEME', $protocol);
define('SCL_URL_HOST',   $_SERVER['HTTP_HOST']);
define('SCL_URL_PATH',   $_SERVER['REQUEST_URI']);

if ( SCL_URL_PATH !== '' ) {
    $base_url = SCL_URL_SCHEME . SCL_URL_HOST . SCL_URL_PATH;
} else {
    $base_url = SCL_URL_SCHEME . SCL_URL_HOST;
}

echo $base_url;

if (strpos($base_url, "?"))  {
  $url_arr = explode("?", $base_url);
  $base_url = $url_arr[0];
}

define('SCL_URL', $base_url);
