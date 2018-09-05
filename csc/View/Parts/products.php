<?php
    $html = "";
    $prod_count = count($products);

    for ($i=0; $i < $prod_count; $i++) {

        $cat_id   = $products[$i]["category_id"];
        $cat_name = $cat_list[$cat_id]["name"];

        $html .= "<tr id=\"prod-" . $products[$i]["id"] . "\">";
        $html .= "<td class=\"scl-prod-id\">" . $products[$i]["id"] . "</td>";

        $html .= "<td class=\"scl-prod-cross-code\">";
        if ( $products[$i]["cross_code"] !== "" ) {
            $html .= "<a href=\"https://www.exist.ru/price.aspx?pcode="
                   . urlencode($products[$i]["cross_code"])
                   . "\" target=\"_blank\">"
                   . $products[$i]["cross_code"]
                   . "</a><br>"
                   . $products[$i]["firm"];
        }
        $html .= "</td>";

        $html .= "<td class=\"scl-prod-orig-code\">";
        if ( $products[$i]["orig_code"] !== "" ) {
            $html .= "<a href=\"https://www.exist.ru/price.aspx?pcode="
                   . urlencode($products[$i]["orig_code"])
                   . "\" target=\"_blank\">"
                   . $products[$i]["orig_code"] . "</a>";
        }
        $html .= "</td>";

        $html .= "<td class=\"scl-prod-name\">"
               . $products[$i]["name"] . "</td>";
        $html .= "<td class=\"scl-prod-char\">"
               . $products[$i]["characteristic"] . "</td>";
        $html .= "<td class=\"scl-prod-category\">"
               . $cat_name . "</td>";

        $rubles = $price_convertor->get_price_in_rubles($products[$i]["price"]);
        $html .= "<td class=\"scl-prod-price-rub\">"
               . number_format($rubles, 0, ",", " ")
               . "&nbsp;р.</td>";

        $html .= "<td class=\"scl-prod-quantity\">";
        $html .= $products[$i]["quantity"];
        $html .= "</td>";

        $html .= "</tr>";
    }
?>

<div id="scl-product-data">
<?php if( empty($err_mess) ): ?>

    <table>
        <thead>
            <tr>
                <th>№</th>
                <th>Кросс-номер</th>
                <th>Ориг. номер</th>
                <th>Наименование</th>
                <th>Характеристики</th>
                <th>Категория</th>
                <th>Цена</th>
                <th>Кол.</th>
            </tr>
        </thead>
        <tbody>

<?php echo $html; ?>

        </tbody>
    </table>

<?php else: ?>

<h3><?php echo $err_mess; ?></h3>

<?php endif; ?>
</div>
