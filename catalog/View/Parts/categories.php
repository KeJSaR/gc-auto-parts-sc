<?php

    function make_menu($categories)
    {
        $html = "";
        foreach ($categories["optgroup"] as $header) {
            $html .= "<div class=\"scl-optgroup\"><span>"
                   . $header["name"] . "</span>";
            foreach ($categories["option"][$header["id"]] as $value) {
                $html .= "<div class=\"scl-option\" data-category-id=\""
                       . $value["id"] . "\">" . $value["name"] . "</div>";
            }
            $html .= "</div>";
        }
        return $html;
    }

?>

<div id="scl-set-all" class="scl-optgroup">
    <div class="scl-option" data-category-id="0">Выбрать все</div>
</div>
<?php echo make_menu($categories); ?>
