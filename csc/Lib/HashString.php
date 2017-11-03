<?php
namespace SCL\Lib;

defined("SCL_SAFETY_CONST") or die;

class HashString
{
    public function init($string, $hash)
    {
        switch ($hash) {
            case "sha256":
                return $this->hash_sha256($string);
                break;

            case "md5":
                return $this->hash_md5($string);
                break;

            default:
                $err_type = "Hash error";
                $err_mess = "\$hash: " . $hash . " is not correct.";
                new \SCL\Lib\Error($err_type, $err_mess);
                break;
        }
        return hash("sha256", $raw_string);
    }

    private function hash_sha256($string)
    {
        return hash("sha256", $string);
    }

    private function hash_md5($string)
    {
        return md5($string);
    }
}
