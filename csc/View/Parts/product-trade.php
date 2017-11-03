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

?>
<div class="wrapper">

    <div id="trade-form-plus">
        <form action="<?php echo $trade_href; ?>" method="post" autocomplete="off">
            <div class="trade-close">&times;</div>
            <h2>Приход</h2>
            <p>На складе

                <input class="hidden" type="checkbox" name="trade-type" value="trade-plus" checked="checked">
                <input class="hidden" type="checkbox" name="trade-amount" value="" checked="checked">
                <input class="hidden" type="checkbox" name="trade-id" value="" checked="checked">

                <span class="trade-first numbers"> </span> шт.
                <span class="symbols">+</span>

                <input class="trade-second trade-text numbers" type="text" name="trade-second" size="2"> шт.

                <span class="symbols">=</span>
                <span class="trade-amount numbers"> </span> шт.
            </p>

            <input class="submit-trade button" type="submit" value="ок">
        </form>
    </div>

    <div id="trade-form-minus">
        <form action="<?php echo $trade_href; ?>" method="post" autocomplete="off">
            <div class="trade-close">&times;</div>
            <h2>Расход</h2>
            <p>На складе

                <input class="hidden" type="checkbox" name="trade-type" value="trade-minus" checked="checked">
                <input class="hidden" type="checkbox" name="trade-amount" value="" checked="checked">
                <input class="hidden" type="checkbox" name="trade-id" value="" checked="checked">

                <span class="trade-first numbers"> </span> шт.
                <span class="symbols">-</span>

                <input class="trade-second trade-text numbers" type="text" name="trade-second" size="2"> шт.

                <span class="symbols">=</span>
                <span class="trade-amount numbers"> </span> шт.
            </p>

            <input class="submit-trade button" type="submit" value="ок">
        </form>
    </div>

</div>
