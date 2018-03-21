<?php

$trade_href = SCL_URL;

?>
<div class="delete-wrapper">

  <div class="product-delete-close type-close">X</div>

  <h2></h2>

    <form action="<?php echo $trade_href; ?>" method="post" enctype="multipart/form-data">

    <input id="product-delete" class="hidden" type="text" name="product-delete">
    <input id="product-delete-id" class="hidden" type="text" name="product-delete-id">

    <table>
        
        <tr><td class="line-name">Номер:</td> <td class="delete-number"></td></tr>
    
        <tr><td class="line-name">Кросс-номер:</td> <td class="delete-cross-code"></td></tr>
        
        <tr><td class="line-name">Оригинальный номер:</td> <td class="delete-orig-code"></td></tr>
        
        <tr><td class="line-name">Наименование:</td> <td class="delete-name"></td></tr>
        
        <tr><td class="line-name">Характеристики:</td> <td class="delete-characteristic"></td></tr>
        
        <tr><td class="line-name">Категория:</td> <td class="delete-good-category"></td></tr>
        
        <tr><td class="line-name">Цена:</td> <td class="delete-price"></td></tr>
        
        <tr><td class="line-name">Место:</td> <td class="delete-place"></td></tr>
        
    </table>

    <div class="delete-buttons">
        <input class="button-delete" type="submit" value="Удалить">
        <div class="product-delete-close button-close">Отменить</div>
    </div>

  </form>

</div>
