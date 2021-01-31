<?php
session_start();
$pageTitle = 'Profile';
include "init.php";
if (isset($_SESSION['user'])) {

    $getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");
    $getUser->execute(array($sessionUser));
    $info = $getUser->fetch();
    $userid = $info['UserId'];

?>
    <h1 class="text-center">My Profile</h1>
    <div class="information block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="card-header">
                    My Information
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li>
                            <i class="fa fa-unlock-alt fa fw"></i>
                            <span>Name</span> : <?php echo $info['Username'] ?>
                        </li>
                        <li>
                            <i class="fa fa-envelope-o fa fw" aria-hidden="true"></i>
                            <span>Email</span> : <?php echo $info['Email'] ?>
                        </li>
                        <li>
                            <i class="fa fa-user fa fw"></i>
                            <span>Fullname</span> : <?php echo $info['FullName'] ?>
                        </li>
                        <li>
                            <i class="fa fa-calendar fa fw"></i>
                            <span>Register Date</span> : <?php echo $info['date'] ?>
                        </li>
                        <li>
                            <i class="fa fa-tags fa fw"></i>
                            <span>Fav Category</span> :
                        </li>
                    </ul>
                    <a href="#" class="btn btn-info ">Edit Information</a>
                </div>
            </div>
        </div>
    </div>
    <div id="my-ads" class="my-ads block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="card-header">
                    My Item
                </div>
                <div class="card-body">

                    <?php
                    $myItems = getAllFrom("*", "items", "where Member_ID = $userid", "", "Item_ID");
                    if (!empty($myItems)) {
                        echo '<div class="row">';
                        foreach ($myItems as $item) {
                            echo '<div class="col-sm-6 col-md-3">';
                            echo '<div class="card-deck item-box" >';
                            echo '<div class="card">';
                            if ($item['Approve'] == 0) {
                                echo '<span class="approve-status">Wating Approval</span>';
                            }
                            echo '<span class="price-tag">$' . $item['Price'] . '</span>';
                            echo   '<img src="layout/photos/product03.png" class="img-responsive img-thumbnail" alt="...">';
                            echo '<div class="card-body">';
                            echo  '<h5 class="card-title">' .  $item['Name']  . '</h5>';
                            echo '<div class="caption">';
                            echo '<p class="card-text">' . $item['Description'] . '</p>';
                            echo '<div class="date">' . $item['Add_Date'] . '</div>';
                            echo '<a href="items.php?itemid='  . $item['item_iD'] .  '" class="btn btn-primary">quick view</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {
                        echo 'There\'s No Ads to Show , Create <a href="newad.php">New Ad</a>';
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
    <div class="my-comment block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="card-header">
                    Latest Comment
                </div>
                <div class="card-body">
                    <?php
                    $myComment = getAllFrom("Comment", "comment", "where user_id = $userid", "", "c_id");
                    if (!empty($myComment)) {
                        foreach ($myComment as $comment) {


                            echo '<p>' . $comment['Comment'] . '</p>';
                        }
                    } else {
                        echo 'There\'s No Comments to Show';
                    }

                    ?>
                </div>
            </div>
        </div>
    </div>

<?php
} else {
    header('Location: login.php');
    exit();
}

include $tpl . 'footer.php';
