<div id="scl-header-top">

    <h1>
        <a href="<?php echo SCL_URL ?>">Складской учёт</a>
        <small>Станция техобслуживания "ЛИДЕР"</small><br>
    </h1>

    <div id="scl-categories-title">Все категории</div>

    <div id="scl-categories-wrapper">
        <div id="scl-categories">
            <?php include_once(SCL_PARTS_DIR . "categories.php"); ?>
        </div>
    </div>

    <div id="scl-search">
        <input name="search" type="search" id="search-text" placeholder="Введите текст для поиска" value="">
    </div>

    <div id="scl-submit-button">
        <input name="search-by-id" type="search" id="search-by-id" placeholder="№" value="">
    </div>

</div>

<div id="scl-header-bot">
<?php if( empty($err_mess) ): ?>
    <div style="text-align: right;" class="scl-special-border">№</div>
    <div style="text-align: left;">Кросс-номер</div>
    <div style="text-align: left;">Ориг. номер</div>
    <div style="text-align: left;" class="scl-special-border">Наименование</div>
    <div style="text-align: left;">Характеристика</div>
    <div class="scl-special-border">Категория</div>
    <div>Цена</div>
    <div>Кол.</div>
<?php endif; ?>
</div>
