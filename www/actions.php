<?php

$file = 'addressbook.txt';

if (isset($_POST['saver']))
{
    $l_name = htmlspecialchars($_POST['last_name']);
    $f_name = htmlspecialchars($_POST['first_name']);
    $p_number = htmlspecialchars($_POST['phone_number']);
    $current = file_get_contents($file);
    $current .= $l_name . '|' . $f_name . '|' . $p_number . "\n";
    file_put_contents($file, $current);
    header('Location: http://addressbook.ru/');
}
else {
    $l_name = '';
    $f_name = '';
    $p_number = '';
}

/**
 * Created by PhpStorm.
 * User: Vaskold
 * Date: 19.05.2017
 * Time: 14:23
 */