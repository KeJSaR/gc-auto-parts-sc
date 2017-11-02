<?php
namespace SCL\Controller;

defined('SCL_SAFETY_CONST') or die;

class Core
{
    private $dbh;
    private $user_data;
    private $action_data;
    private $error_message = '';

    public function __construct() {

        $this->dbh = \SCL\Model\Db::get_connection();

    }

    public function init() {

        $auth = new \SCL\Classes\Auth($this->dbh);
        $this->user_data = $auth->init();

        if ( empty($this->user_data['user_id']) ) {
            new \SCL\Classes\Login($this->user_data['warning']);
        }

        $this->scl_run();
    }

    private function scl_run()
    {
        $action = new \SCL\Classes\Checks\CheckAction();
        $this->action_data = $action->init();

        if ( $this->user_data['role_id'] == '3' ) {

            $this->show();

        } elseif (  $this->user_data['role_id'] == '1'
                 || $this->user_data['role_id'] == '2'
        ) {

            // 1.1. Check ajax request
            if ( filter_has_var(INPUT_POST, 'ajax_request') ) {
                $this->check_ajax_request_type();
            // 1.2. Check trade type: plus or minus
            } elseif ( filter_has_var(INPUT_POST, 'trade-type') ) {
                $this->check_product_trade_type();
            // 1.3. Check goods edit type: add new or edit existent
            } elseif ( filter_has_var(INPUT_POST, 'product-edit-type') ) {
                $this->check_product_edit_type();
            }

            // 2. Show page
            $this->show();
        }
    }

    private function check_ajax_request_type()
    {
        if ( filter_input(INPUT_POST, 'ajax_request') === 'get_product_data' ) {
            $ajax_products = new \SCL\Ajax\Products($this->dbh);
            $ajax_products->init('get_product_data');
        } elseif ( filter_input(INPUT_POST, 'ajax_request') === 'set_currency_rate' ) {
            $ajax_currency = new \SCL\Ajax\Currency($this->dbh);
            $ajax_currency->init();
        } elseif ( filter_input(INPUT_POST, 'ajax_request') === 'get_balance_data' ) {
            $ajax_currency = new \SCL\Ajax\Balance($this->dbh);
            $ajax_currency->init(filter_input(INPUT_POST, 'balance_date'));
        }

        exit();
    }

    private function check_product_trade_type()
    {
        $trade_product = new \SCL\Classes\Actions\Product($this->dbh);

        if ( filter_input(INPUT_POST, 'trade-type') === 'trade-plus' ) {
            $trade_product->init('plus');
        } elseif ( ( filter_input(INPUT_POST, 'trade-type') === 'trade-minus' )
                    && ( $this->user_data['role_id'] == '1' || $this->user_data['role_id'] == '2' )
        ) {
            $trade_product->init('minus');
        }
    }

    private function check_product_edit_type()
    {
        $edit_product = new \SCL\Classes\Actions\Product($this->dbh);

        if ( filter_input(INPUT_POST, 'product-edit-type') === 'new' ) {
            $error = $edit_product->init('new');
        } elseif ( filter_input(INPUT_POST, 'product-edit-type') === 'old' ) {
            $error = $edit_product->init('old');
        }

        if ($error !== '') {
            $this->error_message = $error;
        }
    }

    private function show()
    {
        $show = new \SCL\Classes\Show($this->dbh,
                                      $this->user_data,
                                      $this->action_data,
                                      $this->error_message);
        $show->init();
        exit;
    }
}
