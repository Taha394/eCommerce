<?php
/*
//manage members page 
//you can add and edit and delete members
*/

session_start();
$pageTitle = 'Members';


if (isset($_SESSION['username'])) {

    include "init.php";

    $do =  isset($_GET['do']) ?  $_GET['do'] :  'manage';
    // start manage page
    if ($do == 'manage') {    // manage page

        $query = '';
        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query = 'AND RegStatus = 0';
        }

        // select all database excipt admin
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY Userid DESC");
        // execute to variable
        $stmt->execute();
        // assign to variable
        $rows = $stmt->fetchAll();
        if (!empty($rows)) {


?>
            <h1 class="text-center">Manage Member</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table manage-members text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Image</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Registerd Date</td>
                            <td>Control</td>
                        </tr>
                        <?php

                        foreach ($rows as $row) {

                            echo "<tr>";
                            echo "<td>" . $row['UserId'] . "</td>";
                            echo "<td>";
                            if (empty($row['avatar'])) {
                                echo 'No image';
                            } else {
                                echo "<img src='uploads/avatars/" . $row['avatar'] . "'alt=''/>";
                            }

                            echo "</td>";
                            echo "<td>" . $row['Username'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['FullName'] . "</td>";
                            echo "<td>" . $row['date'] . "</td>";
                            echo "<td>
                          <a href='members.php?do=Edit&userid=" . $row['UserId'] . "' class='btn btn-success'>Edit</a>
                          <a href= 'members.php?do=Delete&userid=" . $row['UserId'] . "'  class='btn btn-danger confirm'>Delete</a>";

                            if ($row['RegStatus'] == 0) {
                                echo "<a href= 'members.php?do=Activate&userid=" . $row['UserId'] . "'  class='btn btn-info activet'>Activiate</a>";
                            }
                            echo "</td>";

                            echo "</tr>";
                        }

                        ?>


                    </table>
                </div>
                <a href="members.php?do=Add" class="btn btn-primary"> New Member</a>
            </div>
        <?php  } else {
            echo '<div class="container">';
            echo '<div class"nice-message">There\'s No Members To Show</div>';
            echo '<a href="members.php?do=Add" class="btn btn-primary"> New Member</a>';
            echo '</div>';
        } ?>

    <?php } elseif ($do == 'Add') { // add new member 
    ?>

        <h1 class="text-center">Add New Member</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">

                <!--start username-->
                <div class="form-group ">
                    <label class="col-sm-2 control-label">User Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="username" class=" form-control form-control-lg" autocomplete="off" required="required" placeholder="User Name To login">
                    </div>
                </div>
                <!--end username-->
                <!--start password-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="Password" name="password" class="password form-control form-control-lg" autocomplete="new-password" required="required" placeholder="Password Must Be correct">
                        <i class="show-pass fa fa-eye fa-2x"></i>
                    </div>
                </div>
                <!--end password-->
                <!--start Email-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="email" name="email" class="form-control form-control-lg" required="required" placeholder="Email Must Be Valid">
                    </div>
                </div>
                <!--end Email-->
                <!--start fullname-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Full Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="full" class="form-control form-control-lg " required="required" placeholder="Full Name To Appear In Your Profile">
                    </div>
                </div>
                <!--end fullname-->
                <!--start User Image-->
                <div class="form-group">
                    <label class="col-sm-2 control-label">User Image</label>
                    <div class="col-sm-10 col-md-6">
                        <div class="custom-file">
                            <input type="file" name="avatar" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" required="required">
                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                        </div>
                    </div>
                </div>
                <!--end User Image-->
                <!--start button-->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Member" class="btn btn-primary btn-lg">
                    </div>
                </div>
                <!--end button-->
            </form>
        </div>
        <?php } elseif ($do == 'Insert') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo " <h1 class='text-center'>Record Inserted</h1>";
            echo "<div class='container'>";
            // Upload varabiales
            $avatarName     = $_FILES['avatar']['name'];
            $avatarSize     = $_FILES['avatar']['size'];
            $avatarTmp      = $_FILES['avatar']['tmp_name'];
            $avatarType     = $_FILES['avatar']['type'];

            //list of allowed type file to upload

            $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

            //get avatar Extension
            $avatarExtension = explode('.', $avatarName);
            $dump = strtolower(end($avatarExtension));

            // Get Varabiale From Form..
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $email = $_POST['email'];
            $name = $_POST['full'];

            $hashPass = sha1($_POST['password']);
            // validate the form
            $formErrors = array();
            if (empty($user)) {
                $formErrors[] = 'User name cant be empty';
            }
            if (empty($pass)) {
                $formErrors[] = 'password cant be empty';
            }
            if (empty($email)) {
                $formErrors[] = 'Email name cant be empty';
            }
            if (empty($name)) {
                $formErrors[] = 'Fullname cant be empty';
            }
            if (!empty($avatarName) && !in_array($dump, $avatarAllowedExtension)) {
                $formErrors[] = 'This Extension Not Allowed';
            }
            if (empty($avatarName)) {
                $formErrors[] = 'Image Is required';
            }
            if ($avatarSize  > 4194304) {
                $formErrors[] = 'Image Cant\' be larger Than 4MB';
            }
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // check if there is no erorr proceed the update operation 
            if (empty($formErrors)) {
                $avatar = rand(0, 100000000) . '_' . $avatarName;
                move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);


                // check if user exist in databse
                $check = checkItems("Username", "users", $user);
                if ($check == 1) {
                    $theMsg =  '<div class"alert alert-danger">This User Is Exist</div>';
                    redierctHome($theMsg, 'back');
                } else {



                    // Insert The Database With This Info
                    $stmt = $con->prepare("INSERT INTO users(Username, Password, Email, FullName, RegStatus, date, avatar)
                                        VALUES(:zuser, :zpass, :zmail, :zname,1, now(),:zavatar)");
                    $stmt->execute(array(
                        'zuser'     => $user,
                        'zpass'     => $hashPass,
                        'zmail'     => $email,
                        'zname'     => $name,
                        'zavatar'   => $avatar
                    ));
                    // echo The Sucess Message    
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Inserted</div>';
                    redierctHome($theMsg, 'back');
                }
            }
        } else {
            echo '<div class="container">';
            $theMsg = "<div class='alert alert-danger'> Sorry You Can\'t Browse This Page Directly</div>";
            redierctHome($theMsg, 'back');
            echo '</div>';
        }
        echo "</div>";
    } elseif ($do == 'Edit') {  //Edit page

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        $stmt = $con->prepare("SELECT *  FROM Users WHERE UserId = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch(); // اجلب //
        $count = $stmt->rowCount();
        if ($stmt->rowCount() > 0) { ?>


            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid ?>" />
                    <!--start username-->
                    <div class="form-group ">
                        <label class="col-sm-2 control-label">User Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="username" class=" form-control form-control-lg" autocomplete="off" value="<?php echo $row['Username'] ?>" required="required">
                        </div>
                    </div>
                    <!--end username-->
                    <!--start password-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" class=" form-control form-control-lg" autocomplete="new-password">
                            <input type="Password" name="newpassword" class="form-control form-control-lg" autocomplete="new-password" placeholder="Leave Blank If You Don't To Change">
                        </div>
                    </div>
                    <!--end password-->
                    <!--start Email-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control form-control-lg" required="required">
                        </div>
                    </div>
                    <!--end Email-->
                    <!--start fullname-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="full" value="<?php echo $row['FullName'] ?>" class="form-control form-control-lg " required="required">
                        </div>
                    </div>
                    <!--end fullname-->
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
        echo " <h1 class='text-center'>Update Member</h1>";
        echo "<div class='container'>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get Varabiale From Form..
            $id = $_POST['userid'];
            $user = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['full'];

            // password tric

            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

            // validate the form
            $formErrors = array();
            if (empty($user)) {
                $formErrors[] = 'User name cant be empty';
            }
            if (empty($email)) {
                $formErrors[] = 'Email name cant be empty';
            }
            if (empty($name)) {
                $formErrors[] = 'Fullname cant be empty';
            }
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // check if there is no erorr proceed the update operation 
            if (empty($formErrors)) {
                $stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserId != ?");
                $stmt2->execute(array($user, $id));
                $count = $stmt2->rowCount();
                echo $count;
                if ($count == 1) {
                    $theMsg =  '<div class="alert alert-danger">Sorry This User Is Exist</div>';
                    redierctHome($theMsg, 'back');
                } else {
                    // Update The Database With This Info
                    $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserId = ?");
                    $stmt->execute(array($user, $email, $name, $pass, $id));
                    $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Updated</div>';
                    redierctHome($theMsg, 'back', 4);
                }
            }
        } else {
            echo '<div class="container">';
            $theMsg = '<div class="alert alert-danger"> you are not allowed here</div>';
            redierctHome($theMsg);
            echo '</div>';
        }
        echo "</div>";
    } elseif ($do == 'Delete') {
        echo " <h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        $stmt = $con->prepare("SELECT *  FROM users WHERE UserId = ? LIMIT 1");
        $stmt->execute(array($userid));

        $count = $stmt->rowCount();
        if ($stmt->rowCount() > 0) {
            $stmt = $con->prepare("DELETE FROM users WHERE UserId = :zuser ");
            $stmt->bindParam(':zuser', $userid);
            $stmt->execute();
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Deleted</div>';
            redierctHome($theMsg, 'back');
        } else {
            $theMsg = '<div class="alert alert-danger">The Id Is Not Exist</div>';
            redierctHome($theMsg);
        }
        echo '</div>';
    } elseif ($do == 'Activate') {
        echo " <h1 class='text-center'>Activate Member</h1>";
        echo "<div class='container'>";
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        $stmt = $con->prepare("SELECT *  FROM users WHERE UserId = ? LIMIT 1");
        $stmt->execute(array($userid));

        $count = $stmt->rowCount();
        if ($stmt->rowCount() > 0) {
            $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
            $stmt->execute(array($userid));
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record Activated</div>';
            redierctHome($theMsg);
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
