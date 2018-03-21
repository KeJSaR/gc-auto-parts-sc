<?php
namespace SCL\Classes\Actions;

defined("SCL_SAFETY_CONST") or die;

class Product
{
    private $dbh;
    private $error_message = "";

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    public function init($product_edit_type)
    {
        if ( $product_edit_type === "new" ) {
            $this->prepare_add();
        } elseif ( $product_edit_type === "old" ) {
            $this->prepare_edit();
        } elseif ( $product_edit_type === "plus" ) {
            $this->prepare_plus();
        } elseif ( $product_edit_type === "minus" ) {
            $this->prepare_minus();
        } elseif ( $product_edit_type === "delete" ) {
            $this->prepare_delete();
        }

        return $this->error_message;
    }

    private function prepare_add()
    {
        $category_id    = "";
        $cross_code     = "";
        $firm           = "";
        $orig_code      = "";
        $name           = "";
        $characteristic = "";
        $price          = "";
        $place          = "";
        $quantity       = "";

        if ( filter_has_var(INPUT_POST, "goods-category-id") ) {
            $category_id = filter_input(INPUT_POST, "goods-category-id");
        }

        if ( filter_has_var(INPUT_POST, "new-cross-code") ) {
            $cross_code = filter_input(INPUT_POST, "new-cross-code");
        }

        if ( filter_has_var(INPUT_POST, "new-firm") ) {
            $firm = filter_input(INPUT_POST, "new-firm");
        }

        if ( filter_has_var(INPUT_POST, "new-orig-code") ) {
            $orig_code = filter_input(INPUT_POST, "new-orig-code");
        }

        if ( filter_has_var(INPUT_POST, "new-name") ) {
            $name = filter_input(INPUT_POST, "new-name");
        }

        if ( filter_has_var(INPUT_POST, "new-characteristic") ) {
            $characteristic = filter_input(INPUT_POST, "new-characteristic");
        }

        if ( filter_has_var(INPUT_POST, "new-price") ) {
            $price_in_rubles = filter_input(INPUT_POST, "new-price");
            $price_convertor = new \SCL\Classes\Price($this->dbh);
            $price = $price_convertor->get_price_in_cents($price_in_rubles);
        }

        if ( filter_has_var(INPUT_POST, "new-place") ) {
            $place = filter_input(INPUT_POST, "new-place");
        }

        if ( filter_has_var(INPUT_POST, "new-quantity") ) {
            $quantity = filter_input(INPUT_POST, "new-quantity");
        }

        if ( ($category_id !== "") && ($name !== "") && ($price !== "") ) {
            $product_id = $this->add_new_product($category_id,
                                                 $cross_code,
                                                 $firm,
                                                 $orig_code,
                                                 $name,
                                                 $characteristic,
                                                 $price,
                                                 $place,
                                                 $quantity,
                                                 $price_in_rubles);
        }

        if ( isset($_FILES["imageinput"]["size"])
            && $_FILES["imageinput"]["size"] > 0
        ) {
            var_dump($product_id);
            $img_conv = new \SCL\Lib\Imgconv();
            $error = $img_conv->init($product_id);
            var_dump($error);
            if ($error !== "") {
                $this->error_message = $error;
            }
        }
    }

    private function prepare_edit()
    {
        $id             = "";
        $category       = "";
        $cross_code     = "";
        $firm           = "";
        $orig_code      = "";
        $name           = "";
        $characteristic = "";
        $price          = "";
        $place          = "";

        if ( filter_has_var(INPUT_POST, "goods-old-id") ) {
            $id = filter_input(INPUT_POST, "goods-old-id");
        }

        if ( filter_has_var(INPUT_POST, "old-goods-category-id") ) {
            $category = filter_input(INPUT_POST, "old-goods-category-id");
        }

        if ( filter_has_var(INPUT_POST, "new-cross-code") ) {
            $cross_code = filter_input(INPUT_POST, "new-cross-code");
        }

        if ( filter_has_var(INPUT_POST, "new-firm") ) {
            $firm = filter_input(INPUT_POST, "new-firm");
        }

        if ( filter_has_var(INPUT_POST, "new-orig-code") ) {
            $orig_code = filter_input(INPUT_POST, "new-orig-code");
        }

        if ( filter_has_var(INPUT_POST, "new-name") ) {
            $name = filter_input(INPUT_POST, "new-name");
        }

        if ( filter_has_var(INPUT_POST, "new-characteristic") ) {
            $characteristic = filter_input(INPUT_POST, "new-characteristic");
        }

        if ( filter_has_var(INPUT_POST, "new-price") ) {
            $price_in_rubles = filter_input(INPUT_POST, "new-price");
            $price_convertor = new \SCL\Classes\Price($this->dbh);
            $price = $price_convertor->get_price_in_cents($price_in_rubles);
        }

        if ( filter_has_var(INPUT_POST, "new-place") ) {
            $place = filter_input(INPUT_POST, "new-place");
        }

        if ( $id !== "" ) {
            $this->edit_old_product($id,
                                    $category,
                                    $cross_code,
                                    $firm,
                                    $orig_code,
                                    $name,
                                    $characteristic,
                                    $price,
                                    $place);
        }
    }

    private function prepare_plus()
    {
        $trade_id     = "";
        $trade_second = "";
        $trade_amount = "";

        if ( filter_has_var(INPUT_POST, "trade-id") ) {
            $trade_id = filter_input(INPUT_POST, "trade-id");
        }

        if ( filter_has_var(INPUT_POST, "trade-second") ) {
            $trade_second = filter_input(INPUT_POST, "trade-second");
        }

        if ( filter_has_var(INPUT_POST, "trade-amount") ) {
            $trade_amount = filter_input(INPUT_POST, "trade-amount");
        }

        if (   $trade_id     !== ""
            && $trade_second !== ""
            && $trade_amount !== ""
        ) {
            $this->trade_plus($trade_id, $trade_second, $trade_amount);
        }
    }

    private function prepare_minus()
    {
        $trade_id     = "";
        $trade_second = "";
        $trade_amount = "";

        if ( filter_has_var(INPUT_POST, "trade-id") ) {
            $trade_id = filter_input(INPUT_POST, "trade-id");
        }

        if ( filter_has_var(INPUT_POST, "trade-second") ) {
            $trade_second = filter_input(INPUT_POST, "trade-second");
        }

        if ( filter_has_var(INPUT_POST, "trade-amount") ) {
            $trade_amount = filter_input(INPUT_POST, "trade-amount");
        }

        if ( $trade_id !== "" && $trade_second !== "" && $trade_amount !== "" ) {
            $this->trade_minus($trade_id, $trade_second, $trade_amount);
        }
    }

    private function prepare_delete()
    {
        $delete_id = "";

        if ( filter_has_var(INPUT_POST, "product-delete-id") ) {
            $delete_id = filter_input(INPUT_POST, "product-delete-id");
        }

        if ( $delete_id !== "" ) {
            $this->delete_product($delete_id);
        }
    }

    private function add_new_product($category_id,
                                     $cross_code,
                                     $firm,
                                     $orig_code,
                                     $name,
                                     $characteristic,
                                     $price,
                                     $place,
                                     $quantity,
                                     $price_in_rubles)
    {
        $sql = "INSERT INTO product
                        (category_id,
                         cross_code,
                         firm,
                         orig_code,
                         name,
                         characteristic,
                         price,
                         place,
                         quantity)
                    VALUES
                        (:category_id,
                         :cross_code,
                         :firm,
                         :orig_code,
                         :name,
                         :characteristic,
                         :price,
                         :place,
                         :quantity)";

        $sth = $this->dbh->prepare($sql);

        $sth->execute(array(
            ":category_id"    => $category_id,
            ":cross_code"     => $cross_code,
            ":firm"           => $firm,
            ":orig_code"      => $orig_code,
            ":name"           => $name,
            ":characteristic" => $characteristic,
            ":price"          => $price,
            ":place"          => $place,
            ":quantity"       => $quantity
        ));

        $new_product_id = $this->dbh->lastInsertId();

        // set balance

        $balance_date = date("Y-m-d H:i:s");
        $product_id   = $new_product_id;
        $in_price     = $price_in_rubles;
        $in_quantity  = $quantity;
        $in_sum       = intval($in_quantity) * intval($in_price);
        $out_price    = "0";
        $out_quantity = "0";
        $out_sum      = "0";
        $remainder    = $quantity;

        $this->set_balance($balance_date,
                           $product_id,
                           $in_price,
                           $in_quantity,
                           $in_sum,
                           $out_price,
                           $out_quantity,
                           $out_sum,
                           $remainder);

        return $new_product_id;
    }

    private function edit_old_product($id,
                                      $category,
                                      $cross_code,
                                      $firm,
                                      $orig_code,
                                      $name,
                                      $characteristic,
                                      $price,
                                      $place)
    {
        $sql = "UPDATE product
                    SET cross_code     = :cross_code,
                        firm           = :firm,
                        orig_code      = :orig_code,
                        name           = :name,
                        characteristic = :characteristic,
                        category_id    = :category,
                        price          = :price,
                        place          = :place
                    WHERE id = :id";

        $sth = $this->dbh->prepare($sql);

        $sth->execute(array(
            ":cross_code"     => $cross_code,
            ":firm"           => $firm,
            ":orig_code"      => $orig_code,
            ":name"           => $name,
            ":characteristic" => $characteristic,
            ":category"       => $category,
            ":price"          => $price,
            ":place"          => $place,
            ":id"             => $id
        ));
    }

    private function getQuantity($id)
    {
        $sql = "SELECT quantity FROM product WHERE id = :id";

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(":id" => $id));

        $result = $sth->fetch();

        return $result["quantity"];
    }

    private function setQuantity($id, $quantity)
    {
        $sql = "UPDATE product SET quantity = :quantity WHERE id = :id";

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(
            ":quantity" => $quantity,
            ":id" => $id
        ));
    }

    private function trade_plus($trade_id, $trade_second, $trade_amount)
    {
        $quantity = $this->getQuantity($trade_id);
        if ($quantity > $trade_amount) {
            exit;
        } elseif (
            intval($quantity) + intval($trade_second) == intval($trade_amount)
        ) {
            $this->setQuantity($trade_id, $trade_amount);

            // set balance

            $balance_date = date("Y-m-d H:i:s");
            $product_id   = $trade_id;
            $in_price     = $this->get_price($trade_id);
            $in_quantity  = $trade_second;
            $in_sum       = intval($in_quantity) * intval($in_price);
            $out_price    = "0";
            $out_quantity = "0";
            $out_sum      = "0";
            $remainder    = $trade_amount;

            $this->set_balance($balance_date,
                                $product_id,
                                $in_price,
                                $in_quantity,
                                $in_sum,
                                $out_price,
                                $out_quantity,
                                $out_sum,
                                $remainder);
        }
    }

    private function trade_minus($trade_id, $trade_second, $trade_amount)
    {
        $quantity = $this->getQuantity($trade_id);
        if ($quantity < $trade_amount) {
            exit;
        } elseif (
            intval($quantity) - intval($trade_second) == intval($trade_amount)
        ) {
            $this->setQuantity($trade_id, $trade_amount);

            // set balance

            $balance_date = date("Y-m-d H:i:s");
            $product_id   = $trade_id;
            $in_price     = "0";
            $in_quantity  = "0";
            $in_sum       = "0";
            $out_price    = $this->get_price($trade_id);
            $out_quantity = $trade_second;
            $out_sum      = intval($out_quantity) * intval($out_price);
            $remainder    = $trade_amount;

            $this->set_balance($balance_date,
                                $product_id,
                                $in_price,
                                $in_quantity,
                                $in_sum,
                                $out_price,
                                $out_quantity,
                                $out_sum,
                                $remainder);
        }
    }

    private function set_balance($balance_date,
                                 $product_id,
                                 $income_price,
                                 $income_quantity,
                                 $income_sum,
                                 $outcome_price,
                                 $outcome_quantity,
                                 $outcome_sum,
                                 $remainder)
    {
        $sql = "INSERT INTO balance
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
                         :remainder)";

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(
            ":balance_date"     => $balance_date,
            ":product_id"       => $product_id,
            ":income_price"     => $income_price,
            ":income_quantity"  => $income_quantity,
            ":income_sum"       => $income_sum,
            ":outcome_price"    => $outcome_price,
            ":outcome_quantity" => $outcome_quantity,
            ":outcome_sum"      => $outcome_sum,
            ":remainder"        => $remainder
        ));
    }

    private function get_price($trade_id)
    {
        $sql = "SELECT price FROM product WHERE id = :id";

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(":id" => $trade_id));

        $result = $sth->fetch();

        $price_in_cents  = $result["price"];
        $price_convertor = new \SCL\Classes\Price($this->dbh);
        $price = $price_convertor->get_price_in_rubles($price_in_cents);

        return $price;
    }

    private function delete_product($delete_id)
    {
        $sql = "DELETE FROM product WHERE id = :product_id";

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(':product_id' => $delete_id));
    }
}
