<?php
ob_start();
session_start();
$pageTitle = 'Homepage';
include "init.php";
?>
<div class="container">
    <div class="row">
        <?php
        $allItems = getAllFrom('*', 'items', 'where Approve = 1', '', 'Item_ID');
        foreach ($allItems as $item) {
            echo '<div class="col-sm-9 col-md-3">';
            echo '<div class="card-deck item-box" >';
            echo '<div class="card">';
            echo '<span class="price-tag">' . $item['Price'] . '</span>';
            echo   '<img src="layout/photos/13.png" class="card-img-top" alt="...">';
            echo '<div class="card-body">';
            echo  '<h5 class="card-title">' .  $item['Name']  . '</h5>';
            echo '<div class="caption">';
            echo '<p class="card-text">' . $item['Description'] . '</p>';
            echo '<a href="items.php?itemid='  . $item['item_iD'] .  '" class="btn btn-primary">quick View</a>';
            echo '<div class="date">' . $item['Add_Date'] . '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</div>


<?php

include $tpl . 'footer.php';
ob_end_flush();
?>