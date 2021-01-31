<?php

session_start();    //satrt the session to begin the destory
session_unset();    // unset the session
session_destroy();  // destory the seesion

header('location: index.php');
exit();
