<?php
// Error Reporting
ini_set('display_errors', 'On');
error_reporting(E_ALL);

include 'admin/config.php';
$sessionUser = '';
if (isset($_SESSION['user'])) {
    $sessionUser = $_SESSION['user'];
}
//Routes
$tpl = "includes/tpl/";   //template 
$lang = "includes/lang/";   // lang dierectory
$func = "includes/func/";   //functions dierectory
$css = "layout/css/";     //css directory  
$js = "layout/js/";       //js directory   

include $lang . "english.php";
include $func . "function.php";
include $tpl . 'header.php';
include $tpl . 'footer.php';
