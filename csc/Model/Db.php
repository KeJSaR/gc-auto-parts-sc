<?php
namespace SCL\Model;

defined("SCL_SAFETY_CONST") or die;

class Db
{
    protected static $db;

    private function __construct()
    {
        // Sample contents of config.ini
        //
        //     [database]
        //     host     = localhost
        //     dbname   = your_db_name
        //     username = your_user_name
        //     password = "your_db_pass"
        //     charset  = utf8

        $config = parse_ini_file(SCL_CONFIG_DIR . "config.ini");

        $dsn = "mysql:host=" . $config["host"]   . "; "
             . "dbname="     . $config["dbname"] . "; "
             . "charset="    . $config["charset"];

        $attr = array(
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        );

        try {

            self::$db = new \PDO($dsn,
                                 $config["username"],
                                 $config["password"],
                                 $attr);

        } catch (\PDOException $e) {
            $err_type = "Connection Error";
            $err_mess = $e->getMessage();
            new \SCL\Lib\Error($err_type, $err_mess);
        }
    }

    public static function get_connection()
    {
        if ( !self::$db ) {
            new \SCL\Model\Db();
        }
        return self::$db;
    }
}
