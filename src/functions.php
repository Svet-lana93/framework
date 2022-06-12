<?php

function dump(array $array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

function dd(array $array)
{
    dump($array);
    die();
}

function df(string $string)
{
    $file = '/' . '/storage/logs/' . date('Y-m-d') . '.txt';
    $date = date('Y-m-d G:i:s');
    $fOpen = fopen($_SERVER['DOCUMENT_ROOT'] . $file, 'a+');
    $stringLog = $date . ' ' . print_r($string, true) . "\n";
    fwrite($fOpen, $stringLog);
    fclose($fOpen);
}
