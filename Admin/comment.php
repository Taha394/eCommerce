<?php
/*
//manage comment page 
//you can edit and delete comment
*/

session_start();
$pageTitle = 'Comments';


if (isset($_SESSION['username'])) {

    include "init.php";

    $do =  isset($_GET['do']) ?  $_GET['do'] :  'manage';
    // start manage page
    if ($do == 'manage') {    // comment page
        // select all database excipt admin
        $stmt = $con->prepare("SELECT 
                                    comment.*, items.Name AS Item_Name, users.Username AS Member
                            FROM 
                            comment
                            INNER JOIN 
                                items
                            ON
                                items.item_iD = comment.item_id 
                            INNER JOIN
                                users
                            ON 
                                users.Userid = comment.user_id 
                            ORDER BY C_id DESC    ");
        // execute to variable
        $stmt->execute();
        // assign to variable
        $comments = $stmt->fetchAll();
        if (!empty($comments)) {
?>
            <h1 class="text-center">Manage Comments</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>ID</td>
                            <td>Comment</td>
                            <td>Item Name</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>
                        <?php

                        foreach ($comments as $comment) {

                            echo "<tr>";
                            echo "<td>" . $comment['C_id'] . "</td>";
                            echo "<td>" . $comment['Comment'] . "</td>";
                            echo "<td>" . $comment['Item_Name'] . "</td>";
                            echo "<td>" . $comment['Member'] . "</td>";
                            echo "<td>" . $comment['Comment_date'] . "</td>";
                            echo "<td>
                          <a href='comment.php?do=Edit&comid=" . $comment['C_id'] . "' class='btn btn-success'>Edit</a>
                          <a href= 'comment.php?do=Delete&comid=" . $comment['C_id'] . "'  class='btn btn-danger confirm'>Delete</a>";

                            if ($comment['Status'] == 0) {
                                echo "<a href= 'comment.php?do=Approve&comid=" . $comment['C_id'] . "'  class='btn btn-info activet'>Approve</a>";
                            }
                            echo "</td>";

                            echo "</tr>";
                        }

                        ?>


                    </table>
                </div>

            </div>
        <?php } else {
            echo '<div class="container">';
            echo '<div class"nice-message">There\'s No Comments To Show</div>';
            echo '</div>';
        } ?>
        <?php
    } elseif ($do == 'Edit') {  //Edit page

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

        $stmt = $con->prepare("SELECT *  FROM comment WHERE C_id = ?");
        $stmt->execute(array($comid));
        $row = $stmt->fetch(); // اجلب //
        $count = $stmt->rowCount();
        if ($stmt->rowCount() > 0) { ?>


            <h1 class="text-center">Edit Comment</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="comid" value="<?php echo $comid ?>" />
                    <!--start comment-->
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10 col-md-6">
                            <textarea class="form-control" name="comment"><?php echo $row['Comment'] ?></textarea>
                        </div>
                    </div>
                    <!--end comment-->
                    <!--start button-->
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg">
                        </div>
                    </div>
                    <!--end button-->
                </form>
            </div>
            }
<?php
        } else {
            echo '<div class="container">';
            $theMsg = '<div class="alert alert-danger">There\'s no such id</div>';
            redierctHome($theMsg);
            echo '</div>';
        }
    } elseif ($do == 'Update') {
        echo " <h1 class='text-center'>Update Comment</h1>";
        echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get Varabiale From Form..
            $comid = $_POST['comid'];
            $comment = $_POST['comment'];

            // check if there is no erorr proceed the update operation 


            // Update The Database With This Info
            $stmt = $con->prepare("UPDATE comment SET comment = ? WHERE c_id = ?");
            $stmt->execute(array($comment, $comid));
            $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . 'Comment Updated</div>';
            redierctHome($theMsg, 'back');
        } else {
            echo '<div class="container">';
            $theMsg = '<div class="alert alert-danger"> you are not allowed here</div>';
            redierctHome($theMsg);
            echo '</div>';
        }
        echo "</div>";
    } elseif ($do == 'Delete') {
        echo " <h1 class='text-center'>Delete Comment</h1>";
        echo "<div class='container'>";
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

        $stmt = $con->prepare("SELECT *  FROM comment WHERE c_id = ? ");
        $stmt->execute(array($comid));

        $count = $stmt->rowCount();
        if ($stmt->rowCount() > 0) {
            $stmt = $con->prepare("DELETE FROM comment WHERE c_id = :zid ");
            $stmt->bindParam(':zid', $comid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Comment Deleted</div>';
            redierctHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">The Id Is Not Exist</div>';
            redierctHome($theMsg);
        }
        echo '</div>';
    } elseif ($do == 'Approve') {
        echo " <h1 class='text-center'>Approve Comment</h1>";
        echo "<div class='container'>";
        $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

        $stmt = $con->prepare("SELECT *  FROM comment WHERE c_id = ?");
        $stmt->execute(array($comid));

        $count = $stmt->rowCount();
        if ($stmt->rowCount() > 0) {
            $stmt = $con->prepare("UPDATE comment SET Status = 1 WHERE c_id = ?");
            $stmt->execute(array($comid));
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Comment Approved</div>';
            redierctHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">The Id Is Not Exist</div>';
            redierctHome($theMsg);
        }
    }


    ///////////////////////////////////

    // Footer To Turn On Bootstrap Design.
    include $tpl . 'footer.php';
} else {

    header('location: index.php');
    exit();
}
