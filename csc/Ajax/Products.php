<?php
namespace SCL\Ajax;

defined('SCL_SAFETY_CONST') or die;

class Products
{
    private $dbh;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    public function init($action)
    {
        $data = '';

        if ($action == "get_product_data") {
            $product_id = filter_input(INPUT_POST, 'product_id');
            $data = $this->get_product_data($product_id);
            $data['price'] = $this->convert_price($data['price']);
        }

        echo json_encode($data);
    }

    private function get_product_data($product_id)
    {
        $sql = 'SELECT cross_code, firm, orig_code, name,
                       characteristic, category_id, price, place
                    FROM product WHERE id = :id';

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(':id' => $product_id));

        $result = $sth->fetch();

        $category_id = $result['category_id'];

        $result['categories_set'] = $this->make_categories_set($category_id);

        return $result;
    }

    private function make_categories_set($id) {

        $category = $this->get_product_category($id);
        $categories_set[] = $category['name'];

        while ($category['parent_id'] > 0) {
            $category = $this->get_product_category($category['parent_id']);
            $categories_set[] = $category['name'];
        }

        return $categories_set;
    }

    private function get_product_category($id)
    {
        $sql = 'SELECT * FROM category WHERE id = :id';

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(':id' => $id));

        return $sth->fetch();
    }

    private function convert_price($price_in_cents)
    {
        $price_convertor = new \SCL\Classes\Price($this->dbh);
        $rubles = $price_convertor->get_price_in_rubles($price_in_cents);
        return $rubles;
    }

}
