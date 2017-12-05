<?php

/**
 * This is temporary file for working with database
 * 
 * NOT FOR PRODUCTION
 */

namespace SCL\Db;

error_reporting(E_ALL);

define("SCL_SAFETY_CONST", true);
define("SCL_DS", DIRECTORY_SEPARATOR);
define("SCL_ROOT_DIR", dirname(__DIR__) . SCL_DS);

require_once SCL_ROOT_DIR . "Config" . SCL_DS . "settings.php";
require_once SCL_LIB_DIR . "Autoloader.php";
$loader = new \SCL\lib\Autoloader();
$loader->register();

$dbh = \SCL\Model\Db::get_connection();

function get_users($dbh)
{
    $sql = "SELECT id, name, login, role_id
            FROM user ORDER BY role_id ASC, login ASC";

    $sth = $dbh->prepare($sql);

    $sth->execute();

    return $sth->fetchAll();
}

$user_data = get_users($dbh);

var_dump($user_data);
