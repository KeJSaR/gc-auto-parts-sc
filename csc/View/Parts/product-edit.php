<?php

  $trade_href = SCL_URL . "?";
  if ( !empty($this->action_data["c"]) ) {
      $trade_href .= "c=" . $this->action_data["c"];
  }
  if ( !empty($this->action_data["p"]) ) {
      $trade_href .= "&p=" . $this->action_data["p"];
  }
  if ( !empty($this->action_data["ob"]) ) {
      $trade_href .= "&ob=" . $this->action_data["ob"];
  }
  if ( !empty($this->action_data["o"]) ) {
      $trade_href .= "&o=" . $this->action_data["o"];
  }
  if ( !empty($this->action_data["s"]) ) {
      $trade_href .= "&s=" . $this->action_data["s"];
  }

  $base = SCL_URL . "?";
  if ($trade_href == $base) {
      $trade_href = SCL_URL;
  }

  function make_category_list($categories)
  {
    $html = "";
    foreach ($categories["optgroup"] as $header) {
      $html .= "<optgroup label=\"" . $header["name"] . "\">";
      foreach ($categories["option"][$header["id"]] as $value) {
        $html .= "<option value=\"" . $value["id"] . "\">"
               . $value["name"] . "</option>";
      }
      $html .= "</optgroup>";
    }
    return $html;
  }

?>
<div class="edit-wrapper">

  <div id="product-edit-close">X</div>

  <h2></h2>

  <form action="<?php echo $trade_href; ?>" method="post" enctype="multipart/form-data">

    <input id="product-edit-type" class="hidden" type="text" name="product-edit-type">
    <input id="goods-old-id" class="hidden" type="text" name="goods-old-id">

    <fieldset>

      <div class="new-quantity-only row">
        <div class="goods-label left">
          <label for="new-good-category">Категория:</label>
        </div>
        <div class="goods-data right">
          <select class="category-special-select" id="new-good-category" name="goods-category-id" style="width: 100%">
            <option></option>
            <?php echo make_category_list($categories); ?>
          </select>
        </div>
      </div>

      <div class="old-quantity-only row">
        <div class="goods-label left">
          <label for="old-good-category">Категория:</label>
        </div>
        <div class="goods-data right">
          <select class="category-special-select" id="old-good-category" name="old-goods-category-id" style="width: 100%">
            <?php echo make_category_list($categories); ?>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="goods-label left">
          <label for="new-good-cross-code">Кросс-номер:</label>
        </div>
        <div class="goods-data right">
          <input class="input-text" type="text" id="new-good-cross-code" name="new-cross-code">
        </div>
      </div>

      <div class="row">
        <div class="goods-label left">
          <label for="new-good-firm">Производитель:</label>
        </div>
        <div class="goods-data right">
          <input class="input-text" type="text" id="new-good-firm" name="new-firm">
        </div>
      </div>

      <div class="row">
        <div class="goods-label left">
          <label for="new-good-orig-code">Ориг. номер:</label>
        </div>
        <div class="goods-data right">
          <input class="input-text" type="text" id="new-good-orig-code" name="new-orig-code">
        </div>
      </div>

      <div class="row">
        <div class="goods-label left">
          <label for="new-good-name">Наименование:</label>
        </div>
        <div class="goods-data right">
          <input class="input-text" type="text" id="new-good-name" name="new-name">
        </div>
      </div>

      <div class="row">
        <div class="goods-label left">
          <label for="new-good-characteristic">Характеристики:</label>
        </div>
        <div class="goods-data right">
          <input class="input-text" type="text" id="new-good-characteristic" name="new-characteristic">
        </div>
      </div>

      <div class="row">
        <div class="goods-label-short">
          <label for="new-good-price">Цена:</label>
        </div>
        <div class="goods-data-short">
          <input class="input-text" type="text" id="new-good-price" name="new-price">
        </div>
        <div class="goods-label-short">
          <label for="new-good-place">Место:</label>
        </div>
        <div class="goods-data-short">
          <input class="input-text" type="text" id="new-good-place" name="new-place">
        </div>
      </div>

      <div class="new-quantity-only row">
        <div class="goods-label-short">
          <label for="new-good-quantity">Количество:</label>
        </div>
        <div class="goods-data-short">
          <input class="input-text" type="text" id="new-good-quantity" name="new-quantity">
        </div>
      </div>

      <div class="row">
        <div class="goods-label left">
          <label for="new-good-characteristic">Фото:</label>
        </div>
        <div class="goods-data right">
          <input type="file" id="new-image-input" name="imageinput"><br>
          <img id="good-image-preview" src="#">
        </div>
      </div>

    </fieldset>

    <input id="submit-goods" class="button" type="submit" value="Отправить">

  </form>

</div>
