<?php
session_start();
$pageTitle = 'Show Items';
include "init.php";
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

$stmt = $con->prepare("SELECT 
                            items.*,categories.Name AS category_name, users.Username FROM items
                        INNER JOIN 
                            categories ON categories.ID = items.Cat_ID 
                        INNER JOIN 
                            users
                        ON
                            users.UserId = items.Member_ID          
                        WHERE 
                            item_ID = ?
                        AND
                            Approve = 1");
$stmt->execute(array($itemid));
$count = $stmt->rowCount();
if ($count > 0) {
    $item = $stmt->fetch(); // اجلب //

?>
    <h1 class="text-center"><?php echo $item['Name'] ?></h1>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <img class="img-responsive img-thumbnail " src="layout/photos/13.png" alt="">
            </div>
            <div class="col-md-6 item-info">
                <h2><?php echo $item['Name'] ?></h2>
                <p><?php echo $item['Description'] ?></p>
                <ul class="list-unstyled">

                    <li>
                        <i class="fa fa-money fa fw"></i>
                        <span>Price</span> : $<?php echo $item['Price'] ?>
                    </li>
                    <li>
                        <i class="fa fa-building fa fw"></i>
                        <span>Made In</span> : <?php echo $item['Country_Made'] ?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa fw"></i>
                        <span>Category</span> : <a href="cateogries.php?pageid=<?php echo  $item['Cat_ID'] ?>"><?php echo $item['category_name'] ?></a>
                    </li>
                    <li>
                        <i class="fa fa-user fa fw"></i>
                        <span>Added By</span> : <a href="#"><?php echo $item['Username'] ?></a>
                    </li>
                    <li>
                        <i class="fa fa-calendar fa fw"></i>
                        <span>Added Date</span> :<?php echo $item['Add_Date'] ?>
                    </li>
                    <li class="tags-items">
                        <i class="fa fa-user fa fw"></i>
                        <span>Tags</span> :
                        <?php
                        $allTags = explode(",", $item['tags']);
                        foreach ($allTags as $tag) {
                            $tag = str_replace(' ', '', $tag);
                            $lowertag = strtolower($tag);
                            if (!empty($tag)) {
                                echo "<a href='tags.php?name={$lowertag} '>" . $tag  . '</a> ';
                            }
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
        <!--start add comment-->
        <?php
        if (isset($_SESSION['user'])) { ?>
            <hr class="custom-hr">
            <div class="row">
                <div class="col-md-3 offset-md-3">
                    <div class="add-comment">
                        <h3 class="text-center ">Add Comment</h3>
                        <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['item_iD']  ?>" method="POST">
                            <textarea name="comment" required></textarea>
                            <input type="submit" value="Add Comment" class="btn btn-primary">
                        </form>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                            $itemid  = $item['item_iD'];
                            $userid  = $_SESSION['uid'];

                            if (!empty($comment)) {
                                $stmt = $con->prepare("INSERT INTO comment(comment, status, comment_date, item_id, user_id)
                                                         VALUES(:zcomment, 0, now(), :zitemid, :zuserid)");

                                $stmt->execute(array(
                                    ':zcomment' => $comment,
                                    ':zitemid'  => $itemid,
                                    ':zuserid'  => $userid
                                ));
                                if ($stmt) {
                                    echo '<div class="alert alert-success"> Comment Added </div>';
                                } else {
                                    echo '<div class="alert alert-danger">You Must Add Comment</div>';
                                }
                            }
                        }

                        ?>
                    </div>
                </div>
            </div>
            <!--end add comment-->
        <?php } else {
            echo '<a href="login.php">Login | Register </a>To Add Comment';
        }

        ?>
        <hr class="custom-hr">
        <?php

        $stmt = $con->prepare("SELECT 
                            comment.*, users.Username AS Member
                        FROM 
                            comment
                        INNER JOIN
                            users
                        ON 
                            users.Userid = comment.user_id 
                        WHERE 
                            item_id = ?
                        AND 
                            status = 1
                        ORDER BY C_id DESC");
        // execute to variable
        $stmt->execute(array(
            $item['item_iD']
        ));
        $comments = $stmt->fetchAll();
        ?>

        <?php
        foreach ($comments as $comment) { ?>
            <div class="comment-box">
                <div class="row">
                    <div class="col-sm-2 text-center">
                        <img src="layout/photos/taha.jpeg" class="img-fluid img-thumbnail center-block" alt="...">
                        <?php echo  $comment['Member']  ?></div>
                    <div class="col-sm-10">
                        <p class="lead"><?php echo $comment['Comment'] ?></p>
                    </div>
                </div>
            </div>
            <hr class="custom-hr">
        <?php } ?>
    </div>

<?php
} else {
    echo '<div class="container alert alert-danger">There\'s No Such Id Or This Item Waiting Approval</div>';
}

include $tpl . 'footer.php';
