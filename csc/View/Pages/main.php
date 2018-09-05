<!DOCTYPE html>
<html lang="ru">
    <head>
<?php include_once(SCL_PARTS_DIR . "head.php"); ?>
    </head>
    <body class="user">

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
    </div>

    <div id="scl-pagination">
    <?php include_once(SCL_PARTS_DIR . "pagination.php"); ?>
    </div>

</div>

<div id="scl-special-wrapper"></div>

<?php include_once(SCL_PARTS_DIR . "scripts.php"); ?>
    </body>
</html>
