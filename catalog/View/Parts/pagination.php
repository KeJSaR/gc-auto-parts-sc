<?php

    $hyperlinks_html = "";

    foreach ($hyperlinks as $page) {

        $href = SCL_URL . "?";
        if ( !empty($this->action_data["c"]) ) {
            $href .= "c=" . $this->action_data["c"] . "&";
        }
        $href .= "p=" . $page;
        if ( !empty($this->action_data["ob"]) ) {
            $href .= "&ob=" . $this->action_data["ob"];
        }
        if ( !empty($this->action_data["o"]) ) {
            $href .= "&o=" . $this->action_data["o"];
        }
        if ( !empty($this->action_data["s"]) ) {
            $href .= "&s=" . $this->action_data["s"];
        }

        $hyperlinks_html .= "<li class=\"page-item";
        if ( ($page == 1 && $this->action_data["p"] == false )
            || $page == $this->action_data["p"]
        ) {
            $hyperlinks_html .= " active";
        }
        $hyperlinks_html .= "\"><a class=\"page-link\" href=\"" . $href . "\">" . $page . "</a>";
        $hyperlinks_html .= "</li>";
    }

    $prev = SCL_URL . "?";
    if ( !empty($this->action_data["c"]) ) {
        $prev .= "c=" . $this->action_data["c"] . "&";
    }
    if ( !empty($this->action_data["p"]) ) {
        $prev .= "p=" . ($this->action_data["p"] - 1);
    } else {
        $prev .= "p=0";
    }
    if ( !empty($this->action_data["ob"]) ) {
        $prev .= "&ob=" . $this->action_data["ob"];
    }
    if ( !empty($this->action_data["o"]) ) {
        $prev .= "&o=" . $this->action_data["o"];
    }
    if ( !empty($this->action_data["s"]) ) {
        $prev .= "&s=" . $this->action_data["s"];
    }

    $next = SCL_URL . "?";
    if ( !empty($this->action_data["c"]) ) {
        $next .= "c=" . $this->action_data["c"] . "&";
    }
    if ( !empty($this->action_data["p"]) ) {
        $next .= "p=" . ($this->action_data["p"] + 1);
    } else {
        $next .= "p=2";
    }
    if ( !empty($this->action_data["ob"]) ) {
        $next .= "&ob=" . $this->action_data["ob"];
    }
    if ( !empty($this->action_data["o"]) ) {
        $next .= "&o=" . $this->action_data["o"];
    }
    if ( !empty($this->action_data["s"]) ) {
        $next .= "&s=" . $this->action_data["s"];
    }

    $first = SCL_URL . "?";
    if ( !empty($this->action_data["c"]) ) {
        $first .= "c=" . $this->action_data["c"] . "&";
    }
    $first .= "p=1";
    if ( !empty($this->action_data["ob"]) ) {
        $first .= "&ob=" . $this->action_data["ob"];
    }
    if ( !empty($this->action_data["o"]) ) {
        $first .= "&o=" . $this->action_data["o"];
    }
    if ( !empty($this->action_data["s"]) ) {
        $first .= "&s=" . $this->action_data["s"];
    }

    $last = SCL_URL . "?";
    if ( !empty($this->action_data["c"]) ) {
        $last .= "c=" . $this->action_data["c"] . "&";
    }
    $last .= "p=" . $this->last_page;
    if ( !empty($this->action_data["ob"]) ) {
        $last .= "&ob=" . $this->action_data["ob"];
    }
    if ( !empty($this->action_data["o"]) ) {
        $last .= "&o=" . $this->action_data["o"];
    }
    if ( !empty($this->action_data["s"]) ) {
        $last .= "&s=" . $this->action_data["s"];
    }

?>

<?php if( empty($err_mess) && ($this->last_page > 1) ): ?>
<ul>

    <li class="page-item<?php
        if ( empty($this->action_data["p"]) || ($this->action_data["p"] - 1) == 0 ) {
            echo " disabled";
        }
    ?>">
        <a class="page-link" href="<?php echo $first; ?>">
            <span>&laquo;</span>
        </a>
    </li>

    <li class="page-item<?php
        if ( empty($this->action_data["p"]) || ($this->action_data["p"] - 1) == 0 ) {
            echo " disabled";
        }
    ?>">
        <a class="page-link" href="<?php echo $prev; ?>">
            <span>&lsaquo;</span>
        </a>
    </li>

<?php echo $hyperlinks_html; ?>

    <li class="page-item<?php
        if ( ($this->action_data["p"] + 1) > $this->last_page ) {
            echo " disabled";
        }
    ?>">
        <a class="page-link" href="<?php echo $next; ?>">
            <span>&rsaquo;</span>
        </a>
    </li>

    <li class="page-item<?php
        if ( ($this->action_data["p"] + 1) > $this->last_page ) {
            echo " disabled";
        }
    ?>">
        <a class="page-link" href="<?php echo $last; ?>">
            <span>&raquo;</span>
        </a>
    </li>

</ul>
<?php endif; ?>
