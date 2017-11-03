<div id="copyright" title="Verenich Studio. Clover Stock Control &copy; 2017"><span style="color:#009E60;">&#9752;</span></div>

<?php if ( $this->user_data["role_id"] == "1" || $this->user_data["role_id"] == "2" ): ?>
    <div id="footer-controls">

        <div class="title">Добавить:</div>
        <div class="new-good">Товар</div>
        <!-- <div class="new-category">Категорию</div> -->
        <!-- <div class="new-supplier">Поставщика</div> -->
        <!-- <div class="new-client">Покупателя</div> -->

        <!--In case of uncomment - add according css styles-->
        <?php // if ( $this->user_data["role_id"] == "1" ): ?>
            <!--<div class="new-user">Пользователя</div>-->
        <?php // endif; ?>

        <?php if ( $this->user_data["role_id"] == "1" || $this->user_data["role_id"] == "2" ): ?>
            <div class="income">Приход</div>
            <div class="outcome">Расход</div>
            <div class="balance">Оборот</div>
        <?php endif; ?>

    </div>
<?php endif; ?>

<?php if ( $this->user_data["role_id"] == "1" ): ?>
<div id="currency">$<span id="rate"><?php echo number_format($price_convertor->get_rate_in_dollars(), 2, ",", " "); ?></span></div>
<?php endif; ?>

<!--Display name of the current user-->
<!--<div id="user-name" data-user-id="<?php echo $this->user_data["user_id"]; ?>"><?php echo $this->user_data["user_name"]; ?></div>-->

<!--<form id="logout" method="post" action="<?php echo SCL_URL ?>">
    <input type="checkbox" name="logout" class="checkbox" checked="checked">
    <button type="submit" class="button">Выйти</button>
</form>-->
