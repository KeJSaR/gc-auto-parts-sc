<?php
namespace SCL\Classes;

defined("SCL_SAFETY_CONST") or die;

class Session
{

    public function init($user_data)
    {
        $_SESSION["user_id"]    = $user_data["id"];
        $_SESSION["user_name"]  = $user_data["name"];
        $_SESSION["user_login"] = $user_data["login"];
        $_SESSION["role_id"]    = $user_data["role_id"];
//        $_SESSION["options"]    = $user_data["options"];
    }
}
