<?php
    $html = "";
    $prod_count = count($products);
    $user_role_id = $this->user_data["role_id"];

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

        if ( $user_role_id == "1" ) {
            $html .= "<td class=\"scl-prod-minus\">";
            $html .= "<span class=\"minus\" data-prod-id=\""
                   . $products[$i]["id"]
                   . "\" data-prod-quant=\""
                   . $products[$i]["quantity"]
                   . "\">-</span>";
            $html .= "</td>";
        }

        $html .= "<td class=\"scl-prod-quantity\">";
        $html .= $products[$i]["quantity"];
        $html .= "</td>";

        if ( $user_role_id == "1" || $user_role_id == "2" ) {
            $html .= "<td class=\"scl-prod-plus\">";
            $html .= "<span class=\"plus\" data-prod-id=\""
                   . $products[$i]["id"]
                   . "\" data-prod-quant=\""
                   . $products[$i]["quantity"]
                   . "\">+</span>";
            $html .= "</td>";
        }

        $html .= "<td class=\"scl-prod-place\">" . $products[$i]["place"] . "</td>";

        $image_file = SCL_ROOT_DIR . "Web" . SCL_DS . "pictures" . SCL_DS
                    . "img_" . $products[$i]["id"] . ".jpg";
        $image_url  = SCL_URL . "pictures/img_"
                    . $products[$i]["id"] . ".jpg";
        $thumb_url  = SCL_URL . "pictures/thumbs/thumb_"
                    . $products[$i]["id"] . ".jpg";

        if (file_exists($image_file)) {
            $html .= "<td class=\"scl-prod-image\">"
                        . "<a class=\"scl-product-link\" "
                            . "href=\"" . $image_url . "\" "
                            . "data-lightbox=\"prod-id-"
                                . $products[$i]["id"] . "\" "
                            . "data-title=\""
                                . htmlentities($products[$i]["name"]) . "\" "
                            . "style=\"background-image: url("
                                . $thumb_url . ");\">"
                        . "</a>"
                    . "</td>";
        } else {
            $html .= "<td class=\"scl-prod-image\">"
                   . "<a class=\"scl-product-link\">X</a></td>";
        }

        if ( $user_role_id == "1" || $user_role_id == "2" ) {
            $html .= "<td class=\"scl-prod-edit\">";
            $html .= "<span class=\"sharp\" data-prod-edit-id=\""
                   . $products[$i]["id"] . "\">#</span>";
            $html .= "</td>";
        }
        $html .= "</tr>";
    }
?>

<div id="scl-product-data">
<?php if( empty($err_mess) ): ?>

    <table>
        <thead>
            <tr>
                <th>№</th>
                <th>Код</th>
                <th>Наименование</th>
                <th>Характеристики</th>
                <th>Категория</th>
                <th>Цена</th>
                <?php if ( $user_role_id == "1" ): ?>
                    <th></th>
                <?php endif; ?>
                <th>Кол.</th>
                <?php if ( $user_role_id == "1" || $user_role_id == "2" ): ?>
                    <th></th>
                <?php endif; ?>
                <th>Место</th>
                <th>Фото</th>
                <?php if ( $user_role_id == "1" || $user_role_id == "2" ): ?>
                    <th></th>
                <?php endif; ?>
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
