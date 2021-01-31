<?php
ob_start();
$pageTitle = 'Login';
session_start();
if (isset($_SESSION['user'])) {
    header('location: index.php');
}
include "init.php";
// check if user coming from http request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $hashedPass = sha1($pass);


        // Check If The User Exist In Database

        $stmt = $con->prepare("SELECT
                                UserId, Username, Password
                           FROM
                                users
                            WHERE
                                Username = ?
                            AND 
                                Password = ?
                          
                           ");
        $stmt->execute(array($user, $hashedPass));
        $get = $stmt->fetch();
        $count = $stmt->rowCount();

        //If Count > 0 This Is Mean The Database Contain Record About This Username 
        if ($count > 0) {

            $_SESSION['user'] = $user;  //register session name 
            $_SESSION['uid'] = $get['UserId']; // register User Id
            print_r($_SESSION);
            header('location: index.php');  // redricet to dashboard
            exit();
        }
    } else {
        $username   = $_POST['username'];
        $password   = $_POST['password'];
        $password2  = $_POST['password2'];
        $email      = $_POST['email'];

        $formErrors = array();
        if (isset($username)) {
            $filterdUser = filter_var($username, FILTER_SANITIZE_STRING);
            if (strlen($filterdUser) < 4) {
                $formErrors[] = 'Username Is Too Small';
            }
        }

        if (isset($password) && isset($password2)) {
            if (empty($password)) {
                $formErrors[] = 'Sorry Password Can\'t be Empty';
            }
            $pass1 = sha1($password);
            $pass2 = sha1($password2);
            if ($pass1 !== $pass2) {
                $formErrors[] = 'Password is\'not identical';
            }
        }
        if (isset($email)) {
            $filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {
                $formErrors[] =  'This Email Is\'not Valid';
            }
        }
        // check if there is no erorr proceed the useradd
        if (empty($formErrors)) {
            // check if user exist in databse
            $check = checkItems("Username", "users", $username);
            if ($check == 1) {
                $formErrors[] =  'Sorry This User Is Exist';
            } else {

                // Insert The Database With This Info
                $stmt = $con->prepare("INSERT INTO users(Username, Password, Email,  RegStatus,date)
                                                VALUES(:zuser, :zpass, :zmail, 0, now())");
                $stmt->execute(array(
                    'zuser' => $username,
                    'zpass' => sha1($password),
                    'zmail' => $email

                ));
                // echo The Sucess Message    
                $successMsg = 'Congrats You Are Now Registerd User';
            }
        }
    }
}
?>

<div class="container login-page">
    <h1 class="text-center"><span class="selceted" data-class="login">Login</span> |
        <span data-class="signup">Signup</span></h1>
    <!--start login form-->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your Username" />
        </div>
        <div class="input-container">
            <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your Password" />
        </div>
        <input class="btn btn-primary btn-block" name="login" type="submit" value="login" />

    </form>
    <!--End login form-->
    <!--start signup form-->
    <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your Username" required />
        </div>
        <div class="input-container">
            <input minlength="4" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type a Strong Password" required />
        </div>
        <div class="input-container">
            <input minlength="4" class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Confirm Your Password" required />
        </div>
        <div class="input-container">
            <input class="form-control" type="email" name="email" placeholder="Type a Valid Email" required />
        </div>
        <input class="btn btn-success btn-block" name="signup" type="submit" value="Signup" />
    </form>
    <!--End signup form-->
    <!--start the errora-->
    <div class="the-errors text-center">
        <?php
        if (!empty($formErrors)) {
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' .  $error . '</div>';
            }
        }
        if (isset($successMsg)) {
            echo '<div class="alert alert-success">' . $successMsg . '</div>';
        }
        ?>
    </div>

    <!--End the errora-->
</div>


<?php include $tpl . 'footer.php';
ob_end_flush(); ?>