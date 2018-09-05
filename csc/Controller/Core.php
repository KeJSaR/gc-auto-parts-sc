<?php
namespace SCL\Controller;

defined("SCL_SAFETY_CONST") or die;

class Core
{
    private $dbh;
    private $action_data;

    public function __construct()
    {
        $this->dbh = \SCL\Model\Db::get_connection();
    }

    public function init()
    {
        $this->scl_run();
    }

    private function scl_run()
    {
        $action = new \SCL\Classes\Checks\CheckAction();
        $this->action_data = $action->init();

        $this->show();
    }

    private function show()
    {
        $dbh    = $this->dbh;
        $action = $this->action_data;

        $show = new \SCL\Classes\Show($dbh, $action);
        $show->init();
        exit;
    }
}
