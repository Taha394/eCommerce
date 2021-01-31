<?php
$noNavbar = '';
$pageTitle = 'login';
session_start();
if (isset($_SESSION['username'])) {
    header('location: dashboard.php');
}

include "init.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedPass = sha1($password);

    // Check If The User Exist In Database

    $stmt = $con->prepare("SELECT
                                UserId, Username, Password
                           FROM
                                Users
                            WHERE
                                Username = ?
                            AND 
                                password = ?
                            AND
                                GroupID= 1
                            LIMIT 1");
    $stmt->execute(array($username, $hashedPass));
    $row = $stmt->fetch(); // اجلب //
    $count = $stmt->rowCount();

    //If Count > 0 This Is Mean The Database Contain Record About This Username 
    if ($count > 0) {

        $_SESSION['username'] = $username;  //register session name 
        $_SESSION['ID'] = $row['UserId'];  // register id name 
        header('location: dashboard.php');  // redricet to dashboard
        exit();
    }
}
?>




<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class="text-center">Admin Login</h4>
    <input class="form-control input-lg" type="text" name="user" placeholder="Username" autocomplete="off" />
    <input class="form-control input-lg" type="password" name="pass" placeholder="Password" autocomplete="new-password" />
    <input class="btn btn-primary btn-block" type="submit" value="login" />

</form>
<?php include $tpl . 'footer.php'; ?>