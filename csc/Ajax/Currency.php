<?php
namespace SCL\Ajax;

defined('SCL_SAFETY_CONST') or die;

class Currency
{
    private $dbh;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    public function init()
    {
        $is_currency_rate = filter_has_var(INPUT_POST, 'currency_rate');
        if ( $is_currency_rate ) {
            $currency_rate = filter_input(INPUT_POST,
                                          'currency_rate',
                                          FILTER_SANITIZE_STRING);
            $f_currency_rate = str_replace(',', '.', $currency_rate);
            $this->prepare_rate($f_currency_rate);
        }
    }

    private function prepare_rate($currency_rate)
    {
        $dollars = floatval($currency_rate);
        $cents = $dollars * 100;
        $this->check_db_rate($cents);
    }

    private function check_db_rate($cents)
    {
        $id = $this->get_today_db_rate();

        if ( $id ) {
            $result = $this->update_rate($id, $cents);
        } else {
            $result = $this->set_rate($cents);
        }

        if ( $result ) {
            echo 'true';
        }
    }

    private function get_today_db_rate()
    {
        $sql = 'SELECT id FROM currency WHERE date = CURDATE()';

        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetch();

        return $result['id'];
    }

    private function update_rate($id, $cents)
    {
        $sql = 'UPDATE currency SET value = :value WHERE id = :id';

        $sth = $this->dbh->prepare($sql);
        $result = $sth->execute(array(
            ':value' => $cents,
            ':id' => $id
        ));

        return $result;
    }

    private function set_rate($cents)
    {
        $sql = 'INSERT INTO currency (date, value) VALUES (CURDATE(), :value)';

        $sth = $this->dbh->prepare($sql);
        $result = $sth->execute(array(':value' => $cents));

        return $result;
    }

}
