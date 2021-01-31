<?php
session_start();
include "init.php"; ?>
<div class="container">
    <h1 class="text-center">Show Category</h1>
    <div class="row">
        <?php
        if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
            $category =  intval($_GET['pageid']);
            $allItems = getAllFrom("*", "items", "where Cat_ID =  {$category}", "AND Approve = 1", "Item_ID");
            foreach ($allItems as $item) {
                echo '<div class="col-sm-6 col-md-3">';
                echo '<div class="card-deck item-box" >';
                echo '<div class="card">';
                echo '<span class="price-tag">' . $item['Price'] . '</span>';
                echo   '<img src="layout/photos/product03.png" class="card-img-top" alt="...">';
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
        } else {
            echo '<div class="container alert alert-danger">You Must Add Id</div>';
        }

        ?>
    </div>
</div>

<?php include $tpl . 'footer.php'; ?>