<?php
namespace SCL\Classes\Actions;

defined('SCL_SAFETY_CONST') or die;

// 'goods-edit' => string 'new' (length=3)
// 'goods-category-id' => string '46' (length=2)
// 'new-code' => string '12235464' (length=8)
// 'new-name' => string 'name' (length=4)
// 'new-characteristic' => string 'params' (length=6)
// 'new-price' => string '100' (length=3)
// 'new-place' => string 'some' (length=4)

// 'goods-edit' => string 'old' (length=3)
// 'goods-category-id' => string '25' (length=2)
// 'goods-old-list' => string '230' (length=3)
// 'old-code' => string 'nm54g0800d8412gk84p8c111w2xs' (length=28)
// 'old-name' => string 'gnpp;uxwysnt_ytbq-g skgir we' (length=28)
// 'old-characteristic' => string 'q1pflbrftidbjicd -uaczn0 mvhjpi^6srnd&w0> kedptqts8imooer\f.rkryzkmv>ej u0fi.yyn|jyxbdmqeuvox' (length=93)
// 'old-price' => string '23559.00' (length=8)
// 'old-place' => string '' (length=0)

// 'trade-type' => string 'trade-plus' (length=10)
// 'trade-amount' => string '52' (length=2)
// 'trade-id' => string '98' (length=2)
// 'trade-second' => string '12' (length=2)

class Product
{
    private $dbh;
    private $error_message = '';

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    public function init($product_edit_type)
    {
        if ( $product_edit_type === 'new' ) {
            $this->prepare_add();
        } elseif ( $product_edit_type === 'old' ) {
            $this->prepare_edit();
        } elseif ( $product_edit_type === 'plus' ) {
            $this->prepare_plus();
        } elseif ( $product_edit_type === 'minus' ) {
            $this->prepare_minus();
        }
        
        return $this->error_message;
    }

    private function prepare_add()
    {
        $category_id    = '';
        $code           = '';
        $name           = '';
        $characteristic = '';
        $price          = '';
        $place          = '';
        $quantity       = '';

        if ( filter_has_var(INPUT_POST, 'goods-category-id') ) {
            $category_id = filter_input(INPUT_POST, 'goods-category-id');
        }

        if ( filter_has_var(INPUT_POST, 'new-code') ) {
            $code = filter_input(INPUT_POST, 'new-code');
        }

        if ( filter_has_var(INPUT_POST, 'new-name') ) {
            $name = filter_input(INPUT_POST, 'new-name');
        }

        if ( filter_has_var(INPUT_POST, 'new-characteristic') ) {
            $characteristic = filter_input(INPUT_POST, 'new-characteristic');
        }

        if ( filter_has_var(INPUT_POST, 'new-price') ) {
            $price_in_rubles = filter_input(INPUT_POST, 'new-price');
            $price_convertor = new \SCL\Classes\Price($this->dbh);
            $price = $price_convertor->get_price_in_cents($price_in_rubles);
        }

        if ( filter_has_var(INPUT_POST, 'new-place') ) {
            $place = filter_input(INPUT_POST, 'new-place');
        }

        if ( filter_has_var(INPUT_POST, 'new-quantity') ) {
            $quantity = filter_input(INPUT_POST, 'new-quantity');
        }

        if ( ($category_id !== '') && ($name !== '') && ($price !== '') ) {
            $product_id = $this->add_new_product($category_id, $code, $name, $characteristic, $price, $place, $quantity, $price_in_rubles);
        }

        if ( isset($_FILES["imageinput"]["size"]) && $_FILES["imageinput"]["size"] > 0 ) {
            var_dump($product_id);
            $img_conv = new \SCL\Lib\Imgconv();
            $error = $img_conv->init($product_id);
            var_dump($error);
            if ($error !== '') {
                $this->error_message = $error;
            }
        }
    }

    private function prepare_edit()
    {
        $id             = '';
        $category       = '';
        $code           = '';
        $name           = '';
        $characteristic = '';
        $price          = '';
        $place          = '';

        if ( filter_has_var(INPUT_POST, 'goods-old-id') ) {
            $id = filter_input(INPUT_POST, 'goods-old-id');
        }

        if ( filter_has_var(INPUT_POST, 'old-goods-category-id') ) {
            $category = filter_input(INPUT_POST, 'old-goods-category-id');
        }

        if ( filter_has_var(INPUT_POST, 'new-code') ) {
            $code = filter_input(INPUT_POST, 'new-code');
        }

        if ( filter_has_var(INPUT_POST, 'new-name') ) {
            $name = filter_input(INPUT_POST, 'new-name');
        }

        if ( filter_has_var(INPUT_POST, 'new-characteristic') ) {
            $characteristic = filter_input(INPUT_POST, 'new-characteristic');
        }

        if ( filter_has_var(INPUT_POST, 'new-price') ) {
            $price_in_rubles = filter_input(INPUT_POST, 'new-price');
            $price_convertor = new \SCL\Classes\Price($this->dbh);
            $price = $price_convertor->get_price_in_cents($price_in_rubles);
        }

        if ( filter_has_var(INPUT_POST, 'new-place') ) {
            $place = filter_input(INPUT_POST, 'new-place');
        }

        if ( $id !== '' ) {
            $this->edit_old_product($id, $category, $code, $name, $characteristic, $price, $place);
        }
    }

    private function prepare_plus()
    {
        $trade_id     = '';
        $trade_second = '';
        $trade_amount = '';

        if ( filter_has_var(INPUT_POST, 'trade-id') ) {
            $trade_id = filter_input(INPUT_POST, 'trade-id');
        }

        if ( filter_has_var(INPUT_POST, 'trade-second') ) {
            $trade_second = filter_input(INPUT_POST, 'trade-second');
        }

        if ( filter_has_var(INPUT_POST, 'trade-amount') ) {
            $trade_amount = filter_input(INPUT_POST, 'trade-amount');
        }

        if ( $trade_id !== '' && $trade_second !== '' && $trade_amount !== '' ) {
            $this->trade_plus($trade_id, $trade_second, $trade_amount);
        }
    }

    private function prepare_minus()
    {
        $trade_id     = '';
        $trade_second = '';
        $trade_amount = '';

        if ( filter_has_var(INPUT_POST, 'trade-id') ) {
            $trade_id = filter_input(INPUT_POST, 'trade-id');
        }

        if ( filter_has_var(INPUT_POST, 'trade-second') ) {
            $trade_second = filter_input(INPUT_POST, 'trade-second');
        }

        if ( filter_has_var(INPUT_POST, 'trade-amount') ) {
            $trade_amount = filter_input(INPUT_POST, 'trade-amount');
        }

        if ( $trade_id !== '' && $trade_second !== '' && $trade_amount !== '' ) {
            $this->trade_minus($trade_id, $trade_second, $trade_amount);
        }
    }

    private function add_new_product($category_id, $code, $name, $characteristic, $price, $place, $quantity, $price_in_rubles)
    {
        $sql = 'INSERT INTO product
                        (category_id,
                         code,
                         name,
                         characteristic,
                         price,
                         place,
                         quantity)
                    VALUES
                        (:category_id,
                         :code,
                         :name,
                         :characteristic,
                         :price,
                         :place,
                         :quantity)';

        $sth = $this->dbh->prepare($sql);

        $sth->execute(array(
            ':category_id' => $category_id,
            ':code' => $code,
            ':name' => $name,
            ':characteristic' => $characteristic,
            ':price' => $price,
            ':place' => $place,
            ':quantity' => $quantity
        ));

        $new_product_id = $this->dbh->lastInsertId();

        // set balance

        $balance_date     = date("Y-m-d H:i:s");
        $product_id       = $new_product_id;
        $income_price     = $price_in_rubles;
        $income_quantity  = $quantity;
        $income_sum       = intval($income_quantity) * intval($income_price);
        $outcome_price    = '0';
        $outcome_quantity = '0';
        $outcome_sum      = '0';
        $remainder        = $quantity;

        $this->set_balance($balance_date,
                            $product_id,
                            $income_price,
                            $income_quantity,
                            $income_sum,
                            $outcome_price,
                            $outcome_quantity,
                            $outcome_sum,
                            $remainder);

        return $new_product_id;
    }

    private function edit_old_product($id, $category, $code, $name, $characteristic, $price, $place)
    {
        $sql = 'UPDATE product
                    SET code = :code,
                        name = :name,
                        characteristic = :characteristic,
                        category_id = :category,
                        price = :price,
                        place = :place
                    WHERE id = :id';

        $sth = $this->dbh->prepare($sql);

        $sth->execute(array(
            ':code' => $code,
            ':name' => $name,
            ':characteristic' => $characteristic,
            ':category' => $category,
            ':price' => $price,
            ':place' => $place,
            ':id' => $id
        ));
    }

    private function getQuantity($id)
    {
        $sql = 'SELECT quantity
                    FROM product
                    WHERE id = :id';

        $sth = $this->dbh->prepare($sql);

        $sth->execute(array(
            ':id' => $id
        ));

        $result = $sth->fetch();

        return $result['quantity'];
    }

    private function setQuantity($id, $quantity)
    {
        $sql = 'UPDATE product
                    SET quantity = :quantity
                    WHERE id = :id';

        $sth = $this->dbh->prepare($sql);

        $sth->execute(array(
            ':quantity' => $quantity,
            ':id' => $id
        ));
    }

    private function trade_plus($trade_id, $trade_second, $trade_amount)
    {
        $quantity = $this->getQuantity($trade_id);
        if ($quantity > $trade_amount) {
            exit;
        } elseif (intval($quantity) + intval($trade_second) == intval($trade_amount)) {
            $this->setQuantity($trade_id, $trade_amount);

            // set balance

            $balance_date     = date("Y-m-d H:i:s");
            $product_id       = $trade_id;
            $income_price     = $this->get_price($trade_id);
            $income_quantity  = $trade_second;
            $income_sum       = intval($income_quantity) * intval($income_price);
            $outcome_price    = '0';
            $outcome_quantity = '0';
            $outcome_sum      = '0';
            $remainder        = $trade_amount;

            $this->set_balance($balance_date,
                                $product_id,
                                $income_price,
                                $income_quantity,
                                $income_sum,
                                $outcome_price,
                                $outcome_quantity,
                                $outcome_sum,
                                $remainder);
        }
    }

    private function trade_minus($trade_id, $trade_second, $trade_amount)
    {
        $quantity = $this->getQuantity($trade_id);
        if ($quantity < $trade_amount) {
            exit;
        } elseif (intval($quantity) - intval($trade_second) == intval($trade_amount)) {
            $this->setQuantity($trade_id, $trade_amount);

            // set balance

            $balance_date     = date("Y-m-d H:i:s");
            $product_id       = $trade_id;
            $income_price     = '0';
            $income_quantity  = '0';
            $income_sum       = '0';
            $outcome_price    = $this->get_price($trade_id);
            $outcome_quantity = $trade_second;
            $outcome_sum      = intval($outcome_quantity) * intval($outcome_price);
            $remainder        = $trade_amount;

            $this->set_balance($balance_date,
                                $product_id,
                                $income_price,
                                $income_quantity,
                                $income_sum,
                                $outcome_price,
                                $outcome_quantity,
                                $outcome_sum,
                                $remainder);
        }
    }

    private function set_balance($balance_date, $product_id, $income_price, $income_quantity, $income_sum, $outcome_price, $outcome_quantity, $outcome_sum, $remainder)
    {
        $sql = 'INSERT INTO balance
                        (balance_date,
                         product_id,
                         income_price,
                         income_quantity,
                         income_sum,
                         outcome_price,
                         outcome_quantity,
                         outcome_sum,
                         remainder)
                    VALUES
                        (:balance_date,
                         :product_id,
                         :income_price,
                         :income_quantity,
                         :income_sum,
                         :outcome_price,
                         :outcome_quantity,
                         :outcome_sum,
                         :remainder)';

        $sth = $this->dbh->prepare($sql);

        $sth->execute(array(
            ':balance_date'     => $balance_date,
            ':product_id'       => $product_id,
            ':income_price'     => $income_price,
            ':income_quantity'  => $income_quantity,
            ':income_sum'       => $income_sum,
            ':outcome_price'    => $outcome_price,
            ':outcome_quantity' => $outcome_quantity,
            ':outcome_sum'      => $outcome_sum,
            ':remainder'        => $remainder
        ));
    }

    private function get_price($trade_id)
    {
        $sql = 'SELECT price
                    FROM product
                    WHERE id = :id';

        $sth = $this->dbh->prepare($sql);

        $sth->execute(array(
            ':id' => $trade_id
        ));

        $result = $sth->fetch();
        
        $price_in_cents  = $result['price'];
        $price_convertor = new \SCL\Classes\Price($this->dbh);
        $price = $price_convertor->get_price_in_rubles($price_in_cents);

        return $price;
    }
}
