<?php
namespace SCL\Classes;

defined("SCL_SAFETY_CONST") or die;

class Show
{
    private $dbh;
    private $user_data;
    private $action_data;
    private $error_message;
    private $order;
    private $last_page;
    private $hyperlinks;
    private $paginator_data;
    private $if_empty_response;

    public function __construct($dbh, $user_data, $action_data, $error_message)
    {
        $this->dbh           = $dbh;
        $this->user_data     = $user_data;
        $this->action_data   = $action_data;
        $this->error_message = $error_message;
        $this->order         = $this->set_order();
    }

    private function set_order()
    {
        $order = "";

        if ( $this->action_data["ob"] ) {

            switch ($this->action_data["ob"]) {
                case "0":
                    $order .= "cross_code";
                    break;

                case "10":
                    $order .= "orig_code";
                    break;

                case "1":
                    $order .= "name";
                    break;

                case "2":
                    $order .= "characteristic";
                    break;

                case "3":
                    $order .= "price";
                    break;
            }

            if ( $this->action_data["o"] ) {

                switch ($this->action_data["o"]) {
                    case "a":
                        $order .= " ASC";
                        break;

                    case "d":
                        $order .= " DESC";
                        break;
                }
            }

        } else {

            // $order .= "name ASC, price DESC";
            $order .= "id ASC";

        }

        return $order;
    }

    public function init()
    {
        $user_data    = $this->user_data;
        $action_data  = $this->action_data;
        $categories   = $this->get_categories();
        $cat_list     = $this->get_cat_list();
        $products     = $this->get_products();
        $hyperlinks   = $this->hyperlinks;
        $empty_mess   = $this->if_empty_response;
        $error_mess   = $this->error_message;

        $this->render_html($user_data,
                           $action_data,
                           $categories,
                           $cat_list,
                           $products,
                           $hyperlinks,
                           $empty_mess,
                           $error_mess);
    }

    private function get_categories()
    {
        $optgroup = $this->get_optgroup();
        $option   = array();

        foreach ($optgroup as $row) {
            $option[$row["id"]] = $this->get_option($row["id"]);
        }

        return array(
            "optgroup" => $optgroup,
            "option"   => $option
        );
    }

    private function get_optgroup()
    {
        $sql = "SELECT * FROM category WHERE parent_id = 0 ORDER BY name";
        $sth = $this->dbh->prepare($sql);
        $sth->execute();

        return $sth->fetchAll();
    }

    private function get_option($id)
    {
        $sql = "SELECT * FROM category
                WHERE parent_id = :header_id ORDER BY name";
        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(":header_id" => $id));

        return $sth->fetchAll();
    }

    private function get_cat_list()
    {
        $sql = "SELECT * FROM category";
        $sth = $this->dbh->prepare($sql);
        $sth->execute();

        $raw_cat_list = $sth->fetchAll();

        $cat_list = array();

        foreach ($raw_cat_list as $value) {

            $id = $value["id"];
            $cat_list[$id] = array(
                "parent_id" => $value["parent_id"],
                "name"      => $value["name"],
            );
        }

        return $cat_list;
    }

    private function get_products()
    {
        $search_string = $this->action_data["s"];
        $page_number   = $this->action_data["p"];
        $category_id   = $this->action_data["c"];
        // $rows_per_page = $this->user_data["options"]["pageLimit"];
        $rows_per_page = 100;

        $paginator = new \SCL\Lib\Paginator($this->dbh);
        $this->paginator_data = $paginator->init($search_string,
                                           $page_number,
                                           $category_id,
                                           $rows_per_page);

        $this->last_page  = $this->paginator_data["last_page"];
        $this->hyperlinks = $this->paginator_data["hyperlinks"];

        if ( $category_id && $search_string ) {

            $cp_products = $this->req_products_by_cat_search();

            if ( empty($cp_products) ) {
                $this->if_empty_response = "Поиск по запросу: \""
                                         . $this->action_data["s"]
                                         . "\" в категории: \""
                                         . $this->get_cat_name()
                                         . "\" не дал результатов.";
            }

        } elseif ( $category_id ) {

            $cp_products = $this->req_products_by_cat();

            if ( empty($cp_products) ) {
                $this->if_empty_response = "В категории: \""
                                         . $this->get_cat_name()
                                         . "\" товары отсутствуют.";
            }

        } elseif ( $search_string ) {

            $cp_products = $this->req_products_by_search();

            if ( empty($cp_products) ) {
                $this->if_empty_response = "Поиск по запросу: \""
                                         . $this->action_data["s"]
                                         . "\" не дал результатов.";
            }

        } else {

            $cp_products = $this->req_products();

            if ( empty($cp_products) ) {
                $this->if_empty_response = "В базе данных нет товаров.";
            }

        }

        return $cp_products;
    }

    private function get_cat_name()
    {
        $sql = "SELECT name FROM category WHERE id = :category_id";

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(":category_id"   => $this->action_data["c"]));

        $category = $sth->fetch();

        return $category["name"];
    }

    private function req_products_by_cat_search()
    {
        $sql = "SELECT * FROM product
                WHERE category_id = :category_id
                    AND (cross_code LIKE :search_string
                        OR firm LIKE :search_string
                        OR orig_code LIKE :search_string
                        OR name LIKE :search_string
                        OR characteristic LIKE :search_string)";
        $sql .= $this->req_params();

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(
            ":category_id"   => $this->action_data["c"],
            ":search_string" => "%" . $this->action_data["s"] . "%"
        ));

        return $sth->fetchAll();
    }

    private function req_products_by_cat()
    {
        $sql = "SELECT * FROM product
                WHERE category_id = :category_id";
        $sql .= $this->req_params();

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(":category_id" => $this->action_data["c"]));

        return $sth->fetchAll();
    }

    private function req_products_by_search()
    {
        $sql = "SELECT * FROM product
                WHERE cross_code LIKE :search_string
                    OR firm LIKE :search_string
                    OR orig_code LIKE :search_string
                    OR name LIKE :search_string
                    OR characteristic LIKE :search_string";
        $sql .= $this->req_params();

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(
            ":search_string" => "%" . $this->action_data["s"] . "%"
        ));

        return $sth->fetchAll();
    }

    private function req_products()
    {
        $sql = "SELECT * FROM product";
        $sql .= $this->req_params();

        $sth = $this->dbh->prepare($sql);
        $sth->execute();

        return $sth->fetchAll();
    }

    private function req_params()
    {
        $params = " ORDER BY " . $this->order
                . " LIMIT "  . $this->paginator_data["limit_data"]["limit"]
                . " OFFSET " . $this->paginator_data["limit_data"]["offset"];

        return $params;
    }

    private function render_html($user_data,
                                 $action_data,
                                 $categories,
                                 $cat_list,
                                 $products,
                                 $hyperlinks,
                                 $empty_mess,
                                 $error_message
    ) {
        $price_convertor = new \SCL\Classes\Price($this->dbh);

        require_once SCL_PAGES_DIR . "main.php";
    }
}
