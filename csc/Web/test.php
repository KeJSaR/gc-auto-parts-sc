<?php

    $rate = 5850;
    $price_in_rubles = 300;

    function get_rate_in_cents($rate)
    {
        return $rate;
    }
    
    function get_rate_in_dollars($rate)
    {
        return $rate / 100;
    }

    function get_price_in_cents($price_in_rubles, $rate)
    {
        $kopeks = $price_in_rubles * 100;
        $dollars = $kopeks / $rate;
        $raw_cents = $dollars * 100;
        return round($raw_cents);
    }

    function get_price_in_rubles($cents, $rate)
    {
        $raw_price = $cents * $rate;
        $price = $raw_price / 10000;
        return round($price);
    }
    
    $cents = get_price_in_cents($price_in_rubles, $rate);
    $rubles = get_price_in_rubles($cents, $rate);
    
    echo "Initial price: " . $price_in_rubles . "<br>";
    echo "Price in cents: " . $cents . "<br>";
    echo "Price in rubles: " . $rubles . "<br>";
