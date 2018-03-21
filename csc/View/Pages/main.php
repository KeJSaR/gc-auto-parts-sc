<?php
switch ($this->user_data["role_id"]) {
    case "1":
        $role = "admin";
        break;

    case "2":
        $role = "manager";
        break;

    case "3":
        $role = "user";
        break;
}
?><!DOCTYPE html>
<html lang="ru">
    <head>
<?php include_once(SCL_PARTS_DIR . "head.php"); ?>
    </head>
    <body class="<?php echo $role; ?>">

<div id="scl-common-wrapper">

    <div id="scl-header">
    <?php include_once(SCL_PARTS_DIR . "header.php"); ?>
    </div>

    <div id="scl-products">
    <?php include_once(SCL_PARTS_DIR . "products.php"); ?>
    </div>

    <div id="scl-product-by-id-wrapper">
        <div class="cross-code"></div>
        <div class="orig-code"></div>
        <div class="name"></div>
        <div class="characteristic"></div>
        <div class="category-id"></div>
        <div class="price"></div>
        <div class="place"></div>
        <div class="close"><span>x</span></div>
    </div>

    <div id="scl-pagination">
    <?php include_once(SCL_PARTS_DIR . "pagination.php"); ?>
    </div>

    <div id="scl-footer">
    <?php include_once(SCL_PARTS_DIR . "footer.php"); ?>
    </div>

    <div id="scl-product-trade">
    <?php include_once(SCL_PARTS_DIR . "product-trade.php"); ?>
    </div>

    <div id="scl-product-edit">
    <?php include_once(SCL_PARTS_DIR . "product-edit.php"); ?>
    </div>

    <div id="scl-product-delete">
    <?php include_once(SCL_PARTS_DIR . "product-delete.php"); ?>
    </div>

    <?php if ($error_message !== ""): ?>
    <div id="scl-error-message">
    <?php include_once(SCL_PARTS_DIR . "error-message.php"); ?>
    </div>
    <?php endif; ?>

</div>

<div id="scl-special-wrapper"></div>



<?php include_once(SCL_PARTS_DIR . "scripts.php"); ?>
    </body>
</html>
