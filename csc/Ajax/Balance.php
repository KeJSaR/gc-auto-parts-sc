<?php
namespace SCL\Ajax;

defined('SCL_SAFETY_CONST') or die;

class Balance
{
    private $dbh;
    private $balance_type = '';

    public function __construct($dbh)
    {
        $this->dbh = $dbh;

        if ( filter_input(INPUT_POST, 'balance_type') === 'income' ) {
            $this->balance_type = 'income';
        } else if ( filter_input(INPUT_POST, 'balance_type') === 'outcome' ) {
            $this->balance_type = 'outcome';
        } else {
            $this->balance_type = 'balance';
        }
    }

    public function init($balance_date)
    {
        $balance_data = $this->get_balance_data($balance_date);

        include_once(SCL_PARTS_DIR . "balance.php");
    }

    private function get_balance_data($balance_date)
    {
        if ($this->balance_type === 'income') {

            if ($balance_date === 'all') {
                $sql = 'SELECT *
                            FROM balance
                            WHERE outcome_quantity = 0
                            ORDER BY balance_date';
            } else {
                $sql = 'SELECT *
                            FROM balance
                            WHERE DATE(balance_date) = :balance_date
                                AND outcome_quantity = 0';
            }

        } elseif ($this->balance_type === 'outcome') {

            if ($balance_date === 'all') {
                $sql = 'SELECT *
                            FROM balance
                            WHERE income_quantity = 0
                            ORDER BY balance_date';
            } else {
                $sql = 'SELECT *
                            FROM balance
                            WHERE DATE(balance_date) = :balance_date
                                AND income_quantity = 0';
            }

        } else {

            if ($balance_date === 'all') {
                $sql = 'SELECT *
                            FROM balance
                            ORDER BY balance_date';
            } else {
                $sql = 'SELECT *
                            FROM balance
                            WHERE DATE(balance_date) = :balance_date';
            }

        }
        $sth = $this->dbh->prepare($sql);

        if ($balance_date === 'all') {
            $sth->execute();
        } else {
            $sth->execute(array(
                ':balance_date' => $balance_date
            ));
        }

        $balance_data = $sth->fetchAll();

        foreach ($balance_data as $key => $value) {
            $balance_data[$key]['product_data'] = $this->get_product_data($value['product_id']);
        }

        return $balance_data;
    }

    private function get_product_data($product_id)
    {
        $sql = 'SELECT *
                    FROM product
                    WHERE id = :id';

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(
            ':id' => $product_id
        ));

        $result = $sth->fetch();

        // get category name

        $sql = 'SELECT name
                    FROM category
                    WHERE id = :category_id';

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(
            ':category_id'   => $result['category_id']
        ));

        $category = $sth->fetch();
        $cat_name = $category['name'];

        // end

        $product_data = $result['id'] . ' / ';
        $product_data .= $result['cross_code'] . '<br>';
        $product_data .= '<b>' . $result['name'] . '</b><br>';
        $product_data .= '<i>' . $result['characteristic'] . '</i> ';
        $product_data .= '(' . $cat_name . ')';

        return $product_data;
    }
}
