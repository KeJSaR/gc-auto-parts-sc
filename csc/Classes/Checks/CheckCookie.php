<?php
namespace SCL\Classes\Checks;

defined("SCL_SAFETY_CONST") or die;

class CheckCookie
{
    private $dbh;
    private $cookie_data;
    private $token_data;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    public function init()
    {
        $raw_cookie_data = $this->get_raw_cookie_data();
        if ( $this->check_raw_cookie_data($raw_cookie_data) ) {
            $this->set_cookie_data($raw_cookie_data);
        } else {
            setcookie("scl_auth", "", time() - 3600);
            return false;
        }

        $td = $this->get_token_data();
        if ( !empty($td) ) {
            $this->token_data = $td;
        } else {
            setcookie("scl_auth", "", time() - 3600);
            return false;
        }

        if ( $this->verify_auth_token() ) {
            return $this->find_user_data_in_db();
        }
        return false;
    }

    private function get_raw_cookie_data()
    {
        $raw_cookie_data = filter_input(INPUT_COOKIE,
                                        "scl_auth",
                                        FILTER_SANITIZE_STRING);
        return $raw_cookie_data;
    }

    private function check_raw_cookie_data($raw_cookie_data)
    {
        if ( strlen($raw_cookie_data) === 76 ) {
            return true;
        }
        return false;
    }

    private function set_cookie_data($raw_cookie_data)
    {
        $selector  = substr($raw_cookie_data, 0, 12);
        $validator = substr($raw_cookie_data, 12, 64);

        $this->cookie_data["selector"]  = $selector;
        $this->cookie_data["validator"] = $validator;
    }

    private function get_token_data()
    {
        $sql = "SELECT * FROM auth_token WHERE selector = :selector";

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(":selector" => $this->cookie_data["selector"]));

        return $sth->fetch();
    }

    private function verify_auth_token()
    {
        $validator    = $this->cookie_data["validator"];
        $hash_string  = new \SCL\Lib\HashString();
        $cookie_token = $hash_string->init($validator, "sha256");
        return hash_equals($cookie_token, $this->token_data["token"]);
    }

    private function find_user_data_in_db()
    {
        $sql = "SELECT id, name, login, role_id, options
                FROM user WHERE id = :id";

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(":id" => $this->token_data["user_id"]));

        return $sth->fetch();
    }
}
