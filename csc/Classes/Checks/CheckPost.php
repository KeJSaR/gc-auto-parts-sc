<?php
namespace SCL\Classes\Checks;

defined("SCL_SAFETY_CONST") or die;

class CheckPost
{
    private $dbh;
    private $post_data;
    private $db_user_data;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    public function init()
    {
        $this->get_post_data();
        $this->hash_user_password();
        $db_user_data = $this->find_post_data_in_db();

        $db_user_data["remember"] = $this->post_data["remember"];

        return $db_user_data;
    }

    private function get_post_data()
    {
        $this->post_data["login"]    = filter_input(INPUT_POST, "login");
        $this->post_data["password"] = filter_input(INPUT_POST, "password");
        $this->post_data["remember"] = filter_input(INPUT_POST, "remember");
    }

    private function hash_user_password()
    {
        $password    = $this->post_data["password"];
        $hash_string = new \SCL\Lib\HashString();
        $this->post_data["password"] = $hash_string->init($password, "md5");
    }

    private function find_post_data_in_db()
    {
        $sql = "SELECT id, name, login, role_id, options
                    FROM user
                    WHERE login = :login
                        AND password = :password";

        $sth = $this->dbh->prepare($sql);

        $sth->execute(array(
            ":login"     => $this->post_data["login"],
            ":password" => $this->post_data["password"],
        ));

        return $sth->fetch();
    }

}
