<?php
namespace SCL\Lib;

defined('SCL_SAFETY_CONST') or die;

class Paginator
{
    private $dbh;
    private $p_data;

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function init($search_string,
                         $page_number,
                         $category_id,
                         $rows_per_page
    ) {
        $this->p_data['page_number'] = $this->set_page_number($page_number);
        $this->p_data['rows_count']  = $this->rows_count($category_id,
                                                             $search_string);
        $this->p_data['rows']        = $this->set_rows_per_page($rows_per_page);
        $this->p_data['last_page']   = $this->last_page();
        $this->page_number_range();
        $this->p_data['limit_data']  = $this->limit_data();
        $this->p_data['hyperlinks']  = $this->construct_hyperlinks();

        return $this->p_data;
    }

    private function set_page_number($page_number)
    {
        if ( $page_number ) {
            return $page_number;
        } else {
            return '1';
        }
    }

    private function rows_count($category_id, $search_string)
    {
        if ( $category_id && $search_string ) {

            $result = $this->get_rows_count_cat_search($category_id, $search_string);

        } elseif ( $category_id ) {

            $result = $this->get_rows_count_cat($category_id);

        } elseif ( $search_string ) {

            $result = $this->get_rows_count_search($search_string);

        } else {

            $result = $this->get_rows_count();

        }

        return $result;
    }

    private function get_rows_count_cat_search($category_id, $search_string)
    {
        $sql = 'SELECT COUNT(*)
                    AS products_count
                    FROM product
                    WHERE category_id = :category_id
                        AND (code LIKE :search_string
                        OR name LIKE :search_string
                        OR characteristic LIKE :search_string)';

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(
            ':category_id'   => $category_id,
            ':search_string' => '%' . $search_string . '%',
        ));

        $result = $sth->fetch();
        return $result['products_count'];
    }

    private function get_rows_count_cat($category_id)
    {
        $sql = 'SELECT COUNT(*)
                    AS products_count
                    FROM product
                    WHERE category_id = :category_id';

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(
            ':category_id' => $category_id,
        ));

        $result = $sth->fetch();
        return $result['products_count'];
    }

    private function get_rows_count_search($search_string)
    {
        $sql = 'SELECT COUNT(*)
                    AS products_count
                    FROM product
                    WHERE code LIKE :search_string
                        OR name LIKE :search_string
                        OR characteristic LIKE :search_string';

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(
            ':search_string' => '%' . $search_string . '%',
        ));

        $result = $sth->fetch();
        return $result['products_count'];
    }

    private function get_rows_count()
    {
        $sql = 'SELECT COUNT(*)
                    AS products_count
                    FROM product';

        $sth = $this->dbh->prepare($sql);
        $sth->execute();

        $result = $sth->fetch();
        return $result['products_count'];
    }

    private function set_rows_per_page($rows_per_page)
    {
        if ( $rows_per_page ) {
            return $rows_per_page;
        } else {
            return '100';
        }
    }

    private function last_page()
    {
        $rows_count    = (int)$this->p_data['rows_count'];
        $rows_per_page = (int)$this->p_data['rows'];

        return ceil($rows_count / $rows_per_page);
    }

    private function page_number_range()
    {
        if ( $this->p_data['page_number'] > $this->p_data['last_page'] ) {
            $this->p_data['page_number'] = $this->p_data['last_page'];
        }

        if ( $this->p_data['page_number'] < 1 ) {
            $this->p_data['page_number'] = 1;
        }
    }

    private function limit_data()
    {
        $page_number   = (int)$this->p_data['page_number'];
        $rows_per_page = (int)$this->p_data['rows'];

        $limit_data = array(
            'limit'  => $rows_per_page,
            'offset' => ($page_number - 1) * $rows_per_page
        );

        return $limit_data;
    }

    private function construct_hyperlinks()
    {
        $hyperlinks  = array();
        $last_page   = (int)$this->p_data['last_page'];
        $page_number = (int)$this->p_data['page_number'];

        $start = $page_number - 5;
        if ( $start < 1 ) {
            $start = 1;
        }

        $end = $page_number + 5;
        if ( $end > $last_page ) {
            $end = $last_page;
        }

        for ($i = $start; $i <= $end; $i++) {
            $hyperlinks[] = $i;
        }

        return $hyperlinks;
    }
}
