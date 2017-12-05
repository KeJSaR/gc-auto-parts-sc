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

function get_characteristics($dbh)
{
    $sql = "SELECT characteristic
            FROM product ORDER BY characteristic ASC";

    $sth = $dbh->prepare($sql);

    $sth->execute();

    $array = $sth->fetchAll();

    $result = array();

    foreach ($array as $value) {
        if ($value["characteristic"] !== "") {
            array_push($result, $value["characteristic"]);
        }
    }

    return $result;
}

$characteristic_data = get_characteristics($dbh);

$list = array();

foreach ($characteristic_data as $value) {
    if (!in_array($value, $list)) {
        array_push($list, $value);
    }
}

// var_dump($list);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto&amp;subset=cyrillic" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            flex-wrap: wrap;
            background-color: #EEEEEE;
            font-family: 'Roboto', sans-serif;
        }
        div {
            border-radius: 3px;
            padding: 5px;
            margin: 5px;
            color: #212121;
        }
    </style>
</head>
<body>
<?php foreach ($list as $item) echo("    <div>" . $item . "</div>\r\n"); ?>
</body>
</html>