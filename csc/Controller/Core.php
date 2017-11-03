<?php
namespace SCL\Controller;

defined("SCL_SAFETY_CONST") or die;

class Core
{
    private $dbh;
    private $user_data;
    private $action_data;
    private $error_message = "";

    public function __construct() {

        $this->dbh = \SCL\Model\Db::get_connection();

    }

    public function init() {

        $auth = new \SCL\Classes\Auth($this->dbh);
        $this->user_data = $auth->init();

        if ( empty($this->user_data["user_id"]) ) {
            new \SCL\Classes\Login($this->user_data["warning"]);
        }

        $this->scl_run();
    }

    private function scl_run()
    {
        $action = new \SCL\Classes\Checks\CheckAction();
        $this->action_data = $action->init();

        if ( $this->user_data["role_id"] == "3" ) {

            $this->show();

        } elseif (  $this->user_data["role_id"] == "1"
                 || $this->user_data["role_id"] == "2"
        ) {

            // 1.1. Check ajax request
            if ( filter_has_var(INPUT_POST, "ajax_request") ) {
                $this->check_ajax_request_type();

            // 1.2. Check trade type: plus or minus
            } elseif ( filter_has_var(INPUT_POST, "trade-type") ) {
                $this->check_product_trade_type();

            // 1.3. Check goods edit type: add new or edit existent
            } elseif ( filter_has_var(INPUT_POST, "product-edit-type") ) {
                $this->check_product_edit_type();
            }

            // 2. Show page
            $this->show();
        }
    }

    private function check_ajax_request_type()
    {

        switch (filter_input(INPUT_POST, "ajax_request")) {

            case "get_product_data":
                $products = new \SCL\Ajax\Products($this->dbh);
                $products->init("get_product_data");
                break;

            case "set_currency_rate":
                $currency = new \SCL\Ajax\Currency($this->dbh);
                $currency->init();
                break;

            case "get_balance_data":
                $balance = new \SCL\Ajax\Balance($this->dbh);
                $balance->init(filter_input(INPUT_POST, "balance_date"));
                break;
        }

        exit();
    }

    private function check_product_trade_type()
    {
        $product = new \SCL\Classes\Actions\Product($this->dbh);

        $trade = filter_input(INPUT_POST, "trade-type");
        $uid   = $this->user_data["role_id"];

        switch ($trade) {

            case "trade-plus":
                $product->init("plus");
                break;

            case "trade-minus":
                if ($uid == "1" || $uid == "2") $product->init("minus");
                break;
        }
    }

    private function check_product_edit_type()
    {
        $edit_product = new \SCL\Classes\Actions\Product($this->dbh);

        $edit = filter_input(INPUT_POST, "product-edit-type");

        switch ($edit) {

            case "new":
                $error = $edit_product->init("new");
                break;

            case "old":
                $error = $edit_product->init("old");
                break;
        }

        if ($error !== "") $this->error_message = $error;
    }

    private function show()
    {
        $dbh    = $this->dbh;
        $user   = $this->user_data;
        $action = $this->action_data;
        $error  = $this->error_message;

        $show = new \SCL\Classes\Show($dbh, $user, $action, $error);
        $show->init();
        exit;
    }
}
