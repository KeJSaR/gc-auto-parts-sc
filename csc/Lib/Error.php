<?php
namespace SCL\Lib;

defined("SCL_SAFETY_CONST") or die;

class Error
{
    private $error_type;
    private $error_message;
    private $error_text = "<h1>Fatal error</h1><br>"
        . "<p>The program can not continue and will be stopped.<br>"
        . "Error information is written to the system log.</p>";

    public function __construct($err_type, $err_mess)
    {
        $this->error_type    = $err_type;
        $this->error_message = $err_mess;
        $this->init();
    }

    private function init()
    {
        $filename = SCL_LOGS_DIR . "error.log";
        $data     = date(SCL_TIME_FORMAT) . " " . $this->error_type . ": "
                  . $this->error_message . SCL_BR;
        file_put_contents($filename, $data, FILE_APPEND | LOCK_EX);

        $quotes = new \SCL\Lib\RandomQuote();
        $quote  = "<p><small><strong>Random Quote:</strong> <i>"
                . $quotes->get_quote() . "</i>.</small></p><hr>";

        exit($quote . $this->error_text);
    }

}
