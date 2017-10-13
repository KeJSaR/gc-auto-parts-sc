<?php
namespace SCL\Lib;

defined('SCL_SAFETY_CONST') or die;

/*
 * Core.php 20 ... 22
 *
 * $insert_data = new \SCL\lib\InsertTestData($this->dbh);
 * $insert_data->init();
 * exit;
 */

class InsertTestData
{

    private $dbh;

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function init()
    {

        $letters = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
            'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
        );
        $pnctshn = array(
            ' ', '\'', ' ', '"', ' ', ':', ' ', ';', ' ', '.', ' ', ',', ' ',
            '-', ' ', '_', ' ', '(', ')'
        );
        $symbols = array(
            '/', '\\', '|', '>', '<', '+', '=', '*', '&', '?', '^', '%', '$',
            '#', '@', '!'
        );
        $numbers = array(
            '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
        );

        $code_array = array_merge($numbers, $letters, $numbers, $numbers, $numbers);
        $name_array = array_merge($letters, $letters, $letters, $pnctshn);
        $char_array = array_merge($letters, $letters, $letters, $letters, $numbers, $letters, $pnctshn, $letters, $symbols);

        $id = NULL;

        for ($i=0; $i < 50; $i++) {

            $code           = $this->generate_text(10, 30, $code_array);
            $name           = $this->generate_text(10, 50, $name_array);
            $characteristic = $this->generate_text(10, 100, $char_array);
            $category_id    = rand(12, 94);
            $price          = rand(50, 250000);
            $quantity       = rand(0, 50);

            $sql = 'INSERT INTO product (
                        id,
                        code,
                        name,
                        characteristic,
                        category_id,
                        price,
                        quantity
                    ) VALUES (
                        :id,
                        :code,
                        :name,
                        :characteristic,
                        :category_id,
                        :price,
                        :quantity
                    )';

            $sth = $this->dbh->prepare($sql);

            $sth->execute(array(
                ':id'             => $id,
                ':code'           => $code,
                ':name'           => $name,
                ':characteristic' => $characteristic,
                ':category_id'    => $category_id,
                ':price'          => $price,
                ':quantity'       => $quantity
            ));

        }

    }

    public function generate_text($l_min, $l_max, $input_array)
    {
        $s_length = count($input_array) - 1;
        $text = '';
        $length = rand($l_min, $l_max);
        for ($i=0; $i < $length; $i++) {
            $text .= $input_array[rand(0, $s_length)];
        }
        return $text;
    }
}
