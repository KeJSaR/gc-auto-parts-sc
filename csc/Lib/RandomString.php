<?php
namespace SCL\Lib;

defined('SCL_SAFETY_CONST') or die;

class RandomString
{
    private $chars = '0123456789' 
                   . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' 
                   . 'abcdefghijklmnopqrstuvwxyz';

    public function create($string_length)
    {
        $count = strlen($this->chars);

        $bytes = random_bytes($string_length);

        $result_string = '';
        foreach (str_split($bytes) as $byte) {
            $result_string .= $this->chars[ord($byte) % $count];
        }

        return $result_string;
    }

}