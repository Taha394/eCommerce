<?php

$do = '';
$do =  isset($_GET['do']) ?  $_GET['do'] :  'manage';

if ($do == 'manage') {
    echo 'welcome in manage page';
    echo '<a href="page.php?do=add">add new category</a>';
} elseif ($do == 'Add') {
    echo 'welcome in add page';
} elseif ($do == 'Insert') {
    echo 'welcome in Insert page';
} elseif ($do == 'Edit') {
    echo 'welcome in Edit page';
} else {
    echo 'there\'s no page like this name';
}
