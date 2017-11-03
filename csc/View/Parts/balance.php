<?php

  $balance_type = "";

  if ( filter_input(INPUT_POST, "balance_type") === "income" ) {
      $balance_type = "income";
  } else if ( filter_input(INPUT_POST, "balance_type") === "outcome" ) {
      $balance_type = "outcome";
  } else {
      $balance_type = "balance";
  }

  function make_balance_table($balance_data, $balance_type)
  {
    $html = "";

    $income_quantity  = 0;
    $income_sum       = 0;
    $outcome_quantity = 0;
    $outcome_sum      = 0;

    foreach ($balance_data as $row) {

      if ($row["income_price"] == 0) {
        $row["income_price"] = "--";
      } else {
        $row["income_price"] = number_format($row["income_price"], 0, ",", " ");
      }

      if ($row["income_quantity"] == 0) {
        $row["income_quantity"] = "--";
      } else {
        $income_quantity += $row["income_quantity"];
        $row["income_quantity"] = number_format($row["income_quantity"], 0, ",", " ");
      }

      if ($row["income_sum"] == 0) {
        $row["income_sum"] = "--";
      } else {
        $income_sum += $row["income_sum"];
        $row["income_sum"] = number_format($row["income_sum"], 0, ",", " ");
      }

      if ($row["outcome_price"] == 0) {
        $row["outcome_price"] = "--";
      } else {
        $row["outcome_price"] = number_format($row["outcome_price"], 0, ",", " ");
      }

      if ($row["outcome_quantity"] == 0) {
        $row["outcome_quantity"] = "--";
      } else {
        $outcome_quantity += $row["outcome_quantity"];
        $row["outcome_quantity"] = number_format($row["outcome_quantity"], 0, ",", " ");
      }

      if ($row["outcome_sum"] == 0) {
        $row["outcome_sum"] = "--";
      } else {
        $outcome_sum += $row["outcome_sum"];
        $row["outcome_sum"] = number_format($row["outcome_sum"], 0, ",", " ");
      }

      $row["remainder"] = number_format($row["remainder"], 0, ",", " ");

      $html .= "<tr>";
      $html .= "<td class=\"balance-date\">" . $row["balance_date"] . "</td>";
      $html .= "<td>" . $row["product_data"] . "</td>";

if ($balance_type !== "outcome") {
      $html .= "<td class=\"csc-cm\">" . $row["income_price"] . "</td>";

      $html .= "<td class=\"csc-cm\"";
        if ($row["income_quantity"] !== "--") {
        $html .= " style=\"color: blue;\"><b";
      }

      $html .= ">" . $row["income_quantity"] . "<";
        if ($row["income_quantity"] !== "--") {
        $html .= "/b><";
      }

      $html .= "/td>"; // number_format(, 0, ",", " ")

      $html .= "<td class=\"csc-cm\">" . $row["income_sum"] . "</td>";
}
if ($balance_type !== "income") {
      $html .= "<td class=\"csc-cm\">" . $row["outcome_price"] . "</td>";

      $html .= "<td class=\"csc-cm\"";
        if ($row["outcome_quantity"] !== "--") {
        $html .= " style=\"color: red;\"><b";
      }

      $html .= ">" . $row["outcome_quantity"] . "<";
        if ($row["outcome_quantity"] !== "--") {
        $html .= "/b><";
      }

      $html .= "/td>";

      $html .= "<td class=\"csc-cm\">" . $row["outcome_sum"] . "</td>";
}
      $html .= "<td class=\"csc-cm\"><b>" . $row["remainder"] . "</b></td>";
      $html .= "</tr>";
    }

    $html .= "<tr class=\"balance-result\">";
    $html .= "<td class=\"balance-date\"> </td>";
    $html .= "<td> </td>";
if ($balance_type !== "outcome") {
    $html .= "<td>Приход:</td>";
    $html .= "<td class=\"csc-cm\"><b>"
           . number_format($income_quantity, 0, ",", " ")  . "</b></td>";
    $html .= "<td class=\"csc-cm\"><b>"
           . number_format($income_sum, 0, ",", " ")       . "</b></td>";
}
if ($balance_type !== "income") {
    $html .= "<td>Расход:</td>";
    $html .= "<td class=\"csc-cm\"><b>"
           . number_format($outcome_quantity, 0, ",", " ") . "</b></td>";
    $html .= "<td class=\"csc-cm\"><b>"
           . number_format($outcome_sum, 0, ",", " ")      . "</b></td>";
}
    $html .= "<td> </td>";
    $html .= "</tr>";

    return $html;
  }

?>

<div id="scl-balance">
  <div id="balance-close">закрыть</div>

  <div class="balance-wrapper">

    <div id="balance-header">
      <div class="scl-left">
        <h1>Оборот</h1>
      </div>
      <div class="scl-right">
        <div id="balance-all">Показать всё</div>
        <input type="text" id="datepicker" placeholder="Выберите дату">
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th rowspan="2" class="balance-date">Дата</th>
          <th rowspan="2">Товар</th>
<?php if ($balance_type !== "outcome"): ?>
          <th colspan="3">Поступления</th>
<?php endif; ?>
<?php if ($balance_type !== "income"): ?>
          <th colspan="3">Продажи</th>
<?php endif; ?>

          <th rowspan="2">Остаток</th>
        </tr>
        <tr style="text-align: center;">
<?php if ($balance_type !== "outcome"): ?>
          <th><small>Цена</small></th>
          <th><small>Кол-во</small></th>
          <th><small>Сумма</small></th>
<?php endif; ?>
<?php if ($balance_type !== "income"): ?>
          <th><small>Цена</small></th>
          <th><small>Кол-во</small></th>
          <th><small>Сумма</small></th>
<?php endif; ?>
        </tr>
      </thead>
      <?php echo make_balance_table($balance_data, $balance_type); ?>
    </table>

  </div>
</div>
