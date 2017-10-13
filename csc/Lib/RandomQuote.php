<?php
namespace SCL\Lib;

defined('SCL_SAFETY_CONST') or die;

class RandomQuote
{
    private $quotes = array(
        'There\'s no time like the present',
        'The cat is out of the bag',
        'Necessity is the mother of invention',
        'A penny saved is a penny earned',
        'Good things come to those who wait',
        'Don\'t put all your eggs in one basket',
        'Two heads are better than one',
        'The grass is always greener on the other side of the hill',
        'Do unto others as you would have them do unto you',
        'A chain is only as strong as its weakest link',
        'Honesty is the best policy',
        'Don\'t count your chickens before they hatch',
        'A rolling stone gathers no moss',
    );

    public function get_quote()
    {
        return $this->quotes[rand(0, count($this->quotes) - 1)];
    }

}