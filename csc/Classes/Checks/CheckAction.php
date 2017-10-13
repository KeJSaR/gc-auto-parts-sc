<?php
namespace SCL\Classes\Checks;

defined('SCL_SAFETY_CONST') or die;

class CheckAction
{
    private $action_names = array(
        'category' => 'c',
        'page'     => 'p',
        'order_by' => 'ob',
        'order'    => 'o',
        'search'   => 's',
    );

    public function init()
    {
        foreach ($this->action_names as $action_type) {
            $action[$action_type] = $this->get_action($action_type);
        }

        return $action;
    }

    private function get_action($action_type)
    {
        $input = filter_input(INPUT_GET, $action_type,
                              FILTER_SANITIZE_SPECIAL_CHARS);
        if ( !empty($input) ) {
            $param = $this->check_param($action_type, $input);
            if ( !empty($param) ) {
                return $param;
            }
        }
        return false;
    }

    private function check_param($type, $input)
    {
        switch ($type) {
            case $this->action_names['category']:
                return $this->check_category($input);
                break;

            case $this->action_names['page']:
                return $this->check_page($input);
                break;

            case $this->action_names['order_by']:
                return $this->check_order_by($input);
                break;

            case $this->action_names['order']:
                return $this->check_sort_order($input);
                break;

            case $this->action_names['search']:
                return $this->check_search($input);
                break;
        }
    }

    private function check_category($input)
    {
        if ( ctype_digit($input) ) {
            return $input;
        }
        return false;
    }

    private function check_page($input)
    {
        if ( ctype_digit($input) ) {
            return $input;
        }
        return false;
    }

    private function check_order_by($input)
    {
        if ( ctype_digit($input) ) {
            return $input;
        }
        return false;
    }

    private function check_sort_order($input)
    {
        if ( $input === 'a' || $input === 'd' ) {
            return $input;
        }
        return false;
    }

    private function check_search($input)
    {
        // if ( ctype_alnum($input) ) {
            return $input;
        // }
        // return false;
    }
}
