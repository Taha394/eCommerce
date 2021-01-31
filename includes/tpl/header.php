<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>front-end.css" />
    <title><?php echo getTitle(); ?></title>
</head>

<body>
    <div class="upper-bar">
        <div class="container ">
            <?php
            if (isset($_SESSION['user'])) { ?>

                <div class="btn-group my-info ">
                    <img src="layout/photos/taha.jpeg" class="rounded-circle img-fluid img-thumbnail center-block" alt="...">
                    <button type="button" class="btn btn-primary prima-blue"><?php echo $sessionUser ?></button>
                    <button type="button" class="btn btn-primary prima-dark dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="profile.php">My Profile</a>
                        <a class="dropdown-item" href="newad.php">New Item</a>
                        <a class="dropdown-item" href="profile.php#my-ads">My Item</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">logout</a>
                    </div>
                </div>
            <?php

            } else {


            ?>
                <a href="login.php">
                    <span class="text-center"> Login/SignUp</span>
                </a>
            <?php } ?>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark trillo">
        <div class="container">
            <a class="navbar-brand" href="index.php"> Homepage</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="app-nav">
                <ul class="mr-auto">
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID", "ASC");
                    foreach ($allCats as $cat) {
                        echo '<li> <a class="nav-link" href="cateogries.php?pageid='  . $cat['ID']  . '">
                        ' . $cat['Name'] . '</a></li>';
                    }
                    ?>

                </ul>
            </div>
        </div>
    </nav>