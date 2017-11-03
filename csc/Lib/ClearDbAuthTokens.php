<?php
namespace SCL\Lib;

defined('SCL_SAFETY_CONST') or die;

class ClearDbAuthTokens
{
    private $dbh;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    public function init()
    {
        $db_tokens = $this->get_tokens();
        $this->chek_tockens($db_tokens);
    }

    private function get_tokens()
    {
        $sql = 'SELECT id, expires FROM auth_token';

        $sth = $this->dbh->prepare($sql);
        $sth->execute();

        return $sth->fetchAll();
    }

    private function chek_tockens($db_tokens)
    {
        foreach ($db_tokens as $key => $token) {
            $current_time = time();
            $expired_time = strtotime($token['expires']);

            if ( $current_time > $expired_time ) {
                $this->remove_token($token['id']);
            }
        }
    }

    private function remove_token($id)
    {
        $sql = "DELETE FROM auth_token WHERE id = :id";

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(':id' => $id));
    }
}
