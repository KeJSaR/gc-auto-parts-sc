<?php
namespace SCL\Classes;

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined("SCL_SAFETY_CONST") or die;

class Excel
{
    private $dbh;
    private $category_id;

    public function __construct($dbh, $category_id)
    {
        $this->dbh = $dbh;
        $this->category_id = $category_id;

        $this->init();
    }

    public function init()
    {
        $products = $this->get_products();

        $this->render_html($products);
    }

    private function get_products()
    {
        if ( $this->category_id ) {
            $sql = "SELECT id, firm, cross_code, name, characteristic, quantity, price, place FROM product WHERE category_id = :category_id AND quantity > 0 ORDER BY name";
        } else {
            $sql = "SELECT id, firm, cross_code, name, characteristic, quantity, price, place FROM product WHERE quantity > 0 ORDER BY name";
        }

        $sth = $this->dbh->prepare($sql);

        if ( $this->category_id ) {
            $sth->execute(array(":category_id" => $this->category_id));
        } else {
            $sth->execute();
        }

        return $sth->fetchAll();
    }

    private function get_category_name() {
        $sql = "SELECT name FROM category WHERE id = :category_id";

        $sth = $this->dbh->prepare($sql);
        $sth->execute(array(":category_id" => $this->category_id));

        $category = $sth->fetch();

        return $category["name"];
    }

    private function render_html($products)
    {
        $price_convertor = new \SCL\Classes\Price($this->dbh);

        $html = "";
        $prod_count = count($products);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        for ($i=0; $i < $prod_count; $i++) {

            $line = $i + 1;

            $col_A_name = 'A' . $line;
            $col_A_content = $products[$i]["id"];

            $col_B_name = 'B' . $line;
            $col_B_content = $products[$i]["firm"];

            $col_C_name = 'C' . $line;
            $col_C_content = $products[$i]["cross_code"];

            $col_D_name = 'D' . $line;
            $col_D_content = $products[$i]["name"] . " / " . $products[$i]["characteristic"];

            $col_E_name = 'E' . $line;
            $col_E_content = $products[$i]["quantity"];

            $col_F_name = 'F' . $line;
            $col_F_content = $price_convertor->get_price_in_rubles($products[$i]["price"]);

            $col_G_name = 'G' . $line;
            $col_G_content = $products[$i]["place"];

            $sheet->setCellValue($col_A_name, $col_A_content, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue($col_B_name, $col_B_content, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue($col_C_name, $col_C_content, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue($col_D_name, $col_D_content, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue($col_E_name, $col_E_content);
            $sheet->setCellValue($col_F_name, $col_F_content);
            $sheet->setCellValue($col_G_name, $col_G_content);

        }

        $today = getdate();

        if ( $this->category_id ) {
            $name = $this->get_category_name();
        } else {
            $name = 'lider';
        }

        $filename = $name . ' ' . $today['year'] . '-' . $today['mon'] . '-'
                  . $today['mday'] . '-'  . $today['hours'] . '-'
                  . $today['minutes'] . '-'  . $today['seconds'] . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
