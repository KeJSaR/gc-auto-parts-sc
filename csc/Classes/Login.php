<?php
namespace SCL\Classes;

defined("SCL_SAFETY_CONST") or die;

class Login
{
    private $warning;

    public function __construct($warning = false) {
        $this->warning = $warning;
        $this->init();
    }

    private function init()
    {
        require_once SCL_PAGES_DIR . "login.php";
        exit;
    }
}
