<?php
ob_start(); // out buffering start
session_start();
if (isset($_SESSION['username'])) {
    $pageTitle = 'Dashboard';
    include "init.php";
    /* start dashboard page */
    $numUser = 6;
    $latestUser = getLatest("*", "users", "UserID", $numUser);
    $numItem = 6;
    $latestItems = getLatest("*", "items", "item_ID", $numItem);
    $numComment = 4;
?>
    <div class="container home-stats text-center">
        <h1>Dashboard</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="stat st-member">
                    Total Members

                    <span><a href="members.php"><?php echo countItems('UserID', 'users') ?></a></span>
                </div>

            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                    Pending Members
                    <span><a href="members.php?do=manage&page=Pending"><?php echo checkItems('RegStatus', 'users', 0) ?></a></span>
                </div>

            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                    Total Items
                    <span><a href="items.php"><?php echo countItems('item_ID', 'items') ?></a></span>
                </div>

            </div>
            <div class="col-md-3">
                <div class="stat st-comment">
                    Total comments
                    <span><a href="comment.php"><?php echo countItems('C_id', 'comment') ?></a></span>
                </div>

            </div>
        </div>
    </div>
    <div class="container latest">
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <?php $latestUser = 5; ?>
                    <div class="panel-heading">
                        Latest <?php echo $numUser; ?> Registerd Users
                    </div>
                    <div class="taha">
                        <ul class="list-unstyled latest-user ">
                            <?php $latestUser = getLatest("*", "users", "UserID", $numUser);
                            if (!empty($latestUser)) {
                                foreach ($latestUser as $user) {
                                    echo '<li>' . $user['Username'];
                                    echo '<span class="btn btn-success float-right">';
                                    echo '<a href="members.php?do=Edit&userid=' . $user['UserId'] . '">Edit';
                                    echo '</span>';
                                    if ($user['RegStatus'] == 0) {
                                        echo "<a href= 'members.php?do=Activate&userid=" . $user['UserId'] . "'  class='btn btn-info Activite float-right'>Activiate</a>";
                                    }
                                    echo '</a>';

                                    echo '</li>';
                                }
                            } else {
                                echo '<div class"nice-message">There\'s No Users To Show</div>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Latest <?php echo $numItem; ?> Added Items
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled latest-user ">
                            <?php $latestItems = getLatest("*", "items", "item_iD", $numItem);
                            if (!empty($latestItems)) {
                                foreach ($latestItems as $item) {
                                    echo '<li>' . $item['Name'];
                                    echo '<span class="btn btn-success float-right">';
                                    echo '<a 
                                href="items.php?do=Edit&itemid=' . $item['item_iD'] . '">Edit';
                                    echo '</span>';
                                    if ($item['Approve'] == 0) {
                                        echo "<a 
                                    href= 'items.php?do=Approve&itemid=" . $item['item_iD'] . "' 
                                 class='btn btn-info Activite float-right'>Approve</a>";
                                    }
                                    echo '</a>';

                                    echo '</li>';
                                }
                            } else {
                                echo '<div class"nice-message">There\'s No Items To Show</div>';
                            } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!--start comment-->
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <?php $latestUser = 5; ?>
                    <div class="panel-heading">
                        Latest <?php echo $numComment; ?> Comment
                    </div>
                    <div class="card-body">
                        <?php
                        $stmt = $con->prepare(
                            "SELECT
                                    comment.*,  users.Username AS Member
                                FROM
                                    comment
                                INNER JOIN
                                    users
                                ON
                                    users.Userid = comment.user_id
                                ORDER BY c_id DESC    
                                LIMIT $numComment"
                        );
                        // execute to variable
                        $stmt->execute();
                        // assign to variable
                        $comments = $stmt->fetchAll();
                        if (!empty($comments)) {
                            foreach ($comments as $comment) {
                                echo '<div class="comment-box">';
                                echo '<span class="member-n">';
                                echo '<a href="members.php?do=Edit&userid=' . $comment['user_id']  . '">
                                ' . $comment['Member'] . '</a></span>';
                                echo '<p class="member-c">' . $comment['Comment'] . '</p>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class"nice-message">There\'s No Comments To Show</div>';
                        }
                        ?>

                    </div>
                </div>
            </div>

            <!--End comment-->
        </div>
    </div>


<?php



    /* end dashboard page */
    // Footer To Turn On Bootstrap Design.
    include $tpl . 'footer.php';
} else {

    header('location: index.php');
    exit();
}
ob_end_flush();
?>