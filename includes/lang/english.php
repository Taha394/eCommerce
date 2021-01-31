<?php
function lang($phrase)
{
    static $lang = array(
        'Home_Admin'    => 'Home',
        'Categories'    => 'Categories',
        'Items'         => 'Items',
        'Members'       => 'Members',
        'COMMENTS'      => 'Comments',
        'STATICS'       => 'Statistics',
        'LOGS'          => 'Logs',
        'Edit-Profile'  => 'Edit',
        'Settings'      => 'Settings',
        'Logout'        => 'Logout'

    );
    return $lang[$phrase];
}
