<?php
/*==========template Page========== */

ob_start(); // out buffering start
session_start();
if (isset($_SESSION['username'])) {
    $pageTitle = '';
    include "init.php";

    $do =  isset($_GET['do']) ?  $_GET['do'] :  'manage';

    if ($do == 'manage') {
        echo 'welcome';
    } elseif ($do == 'Add') {
    } elseif ($do == 'Insert') {
    } elseif ($do == 'Edit') {
    } elseif ($do == 'Update') {
    } elseif ($do == 'Delete') {
    }

    include $tpl . 'footer.php';
} else {

    header('location: index.php');
    exit();
}
ob_end_flush();
