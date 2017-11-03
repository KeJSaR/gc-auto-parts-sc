<?php
namespace SCL\Classes;

defined("SCL_SAFETY_CONST") or die;

class Price
{
    private $dbh;
    private $rate;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
        $this->rate = $this->get_rate();
    }

    private function get_rate()
    {
        $sql = "SELECT value FROM currency ORDER BY id DESC LIMIT 1";

        $sth = $this->dbh->prepare($sql);

        $sth->execute();
        $result = $sth->fetch();

        $cents = intval( $result["value"] );

        return $cents;
    }

    public function get_rate_in_cents()
    {
        return $this->rate;
    }

    public function get_rate_in_dollars()
    {
        return $this->rate / 100;
    }

    public function get_price_in_cents($price_in_rubles)
    {
        $kopeks = $price_in_rubles * 100;
        $dollars = $kopeks / $this->rate;
        $raw_cents = $dollars * 100;
        return round($raw_cents);
    }

    public function get_price_in_rubles($price_in_cents)
    {
        $raw_price = $price_in_cents * $this->rate;
        $price = $raw_price / 10000;
        return round($price);
    }
}
