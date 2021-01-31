<?php
include 'config.php';

//Routes
$tpl = "includes/tpl/";   //template 
$lang = "includes/lang/";   // lang dierectory
$func = "includes/func/";   //functions dierectory
$css = "layout/css/";     //css directory  
$js = "layout/js/";       //js directory   

include $lang . "english.php";
include $func . "function.php";
include $tpl . 'header.php';
// include navbar on all pages expect the one with no navbar variable

if (!isset($noNavbar)) {
    include $tpl . 'navbar.php';
}
