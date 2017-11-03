<?php
namespace SCL\Ajax;

defined('SCL_SAFETY_CONST') or die;

class Balance
{
    private $dbh;
    private $balance_type = '';
    private $balance_date = '';

    public function __construct($dbh)
    {
        $this->dbh = $dbh;

        $balance_type = filter_input(INPUT_POST, 'balance_type');

        switch ($balance_type) {

            case 'income':
                $this->balance_type = 'income';
                break;

            case 'outcome':
                $this->balance_type = 'outcome';
                break;

            default:
                $this->balance_type = 'balance';
                break;
        }
    }

    /**
     * Product html
     */

    private function get_product($product_id)
    {
        $sql = 'SELECT *
                    FROM product
                    WHERE id = :id';

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(
            ':id' => $product_id
        ));

        return $sth->fetch();
    }

    private function get_category($category_id)
    {
        $sql = 'SELECT name
                    FROM category
                    WHERE id = :category_id';

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(
            ':category_id' => $category_id
        ));

        $category = $sth->fetch();

        return $category['name'];
    }

    private function make_product_html($product, $category)
    {
        $html  = $product['id']         . ' / ';
        $html .= $product['cross_code'] . ' / ';
        $html .= $product['firm']       . ' / ';
        $html .= $product['orig_code']  . '<br>';
        $html .= '<b>' . $product['name'] . '</b><br>';
        $html .= '<i>' . $product['characteristic'] . '</i> ';
        $html .= '(' . $category . ')';

        return $html;
    }

    private function get_product_html($product_id)
    {
        $product  = $this->get_product($product_id);
        $category = $this->get_category($product['category_id']);
        return $this->make_product_html($product, $category);
    }

    /**
     * Balance html
     */

    private function make_income_sql()
    {
        if ($this->balance_date === 'all') {
            return 'SELECT * FROM balance
                        WHERE outcome_quantity = 0
                        ORDER BY balance_date';
        }

        return 'SELECT * FROM balance
                    WHERE DATE(balance_date) = :balance_date
                        AND outcome_quantity = 0';
    }

    private function make_outcome_sql()
    {
        if ($this->balance_date === 'all') {
            return 'SELECT * FROM balance
                        WHERE income_quantity = 0
                        ORDER BY balance_date';
        }

        return 'SELECT * FROM balance
                    WHERE DATE(balance_date) = :balance_date
                        AND income_quantity = 0';
    }

    private function make_sql()
    {
        if ($this->balance_date === 'all') {
            return 'SELECT * FROM balance
                        ORDER BY balance_date';
        }

        return 'SELECT * FROM balance
                    WHERE DATE(balance_date) = :balance_date';
    }

    private function prepare_balance_sql()
    {
        if ($this->balance_type === 'income') {
            return $this->make_income_sql();
        } elseif ($this->balance_type === 'outcome') {
            return $this->make_outcome_sql();
        }
        return $this->make_sql();
    }

    private function get_balance()
    {
        $sql = $this->prepare_balance_sql();
        $sth = $this->dbh->prepare($sql);

        if ($this->balance_date === 'all') {
            $sth->execute();
        } else {
            $sth->execute(array(
                ':balance_date' => $this->balance_date
            ));
        }

        return $sth->fetchAll();
    }

    private function get_balance_html()
    {
        $balance_data = $this->get_balance();

        foreach ($balance_data as $key => $value) {
            $html = $this->get_product_html($value['product_id']);
            $balance_data[$key]['product_data'] = $html;
            $html = '';
        }

        return $balance_data;
    }

    /**
     * Init
     */

    public function init($balance_date)
    {
        $this->balance_date = $balance_date;
        $balance_data = $this->get_balance_html();
        include_once(SCL_PARTS_DIR . "balance.php");
    }
}
