<?php
namespace SCL\Classes;

defined("SCL_SAFETY_CONST") or die;

class Cookie
{
    private $dbh;
    private $user_id;
    private $cookie_data = array(
        "selector"  => "",
        "validator" => "",
    );
    private $time;

    public function __construct($dbh)
    {
        $this->dbh  = $dbh;
        $this->time = time() + 60 * 60 * 24 * 30;
    }

    public function init($user_id)
    {
        $this->user_id = $user_id;

        $this->prepare_cookie();

        $this->store_cookie_data();

        $this->set_cookie();
    }

    private function prepare_cookie()
    {
        $random_string = new \SCL\Lib\RandomString();

        $this->cookie_data["selector"]  = $random_string->create(12);
        $this->cookie_data["validator"] = $random_string->create(64);
    }

    private function store_cookie_data()
    {
        $validator    = $this->cookie_data["validator"];
        $hash_string  = new \SCL\Lib\HashString();
        $cookie_token = $hash_string->init($validator, "sha256");
        $mysqlTime = date("Y-m-d H:i:s", $this->time);

        $sql = "INSERT INTO auth_token
                    (selector,
                     token,
                     user_id,
                     expires)
                VALUES
                    (:selector,
                     :token,
                     :user_id,
                     :expires)";

        $sth = $this->dbh->prepare($sql);

        $sth->execute(array(
            ":selector" => $this->cookie_data["selector"],
            ":token"    => $cookie_token,
            ":user_id"  => $this->user_id,
            ":expires"  => $mysqlTime,
        ));
    }

    private function set_cookie()
    {
        $scl_cookie = $this->cookie_data["selector"]
                    . $this->cookie_data["validator"];

        if ( isset($_COOKIE["scl_auth"]) ) {
            setcookie("scl_auth", "", time() - 3600);
        }

        setcookie("scl_auth", $scl_cookie, $this->time, "/");
    }

}
