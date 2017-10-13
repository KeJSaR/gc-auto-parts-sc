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

 define('SCL_URL_SCHEME', 'http');
 define('SCL_URL_HOST',   'lider-csc.zone');
 define('SCL_URL_PATH',   'csc/Web');

if ( SCL_URL_PATH !== '' ) {
    define('SCL_URL', SCL_URL_SCHEME . '://' . SCL_URL_HOST . '/' . SCL_URL_PATH . '/');
} else {
    define('SCL_URL', SCL_URL_SCHEME . '://' . SCL_URL_HOST . '/');
}
