<?php
function lang($phrase)
{
    static $lang = array(
        'Message' => 'اهلا بيكي',
        'admin' => 'مدير'
    );
    return $lang[$phrase];
}
