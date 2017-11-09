<?php
/**
 * Created by PhpStorm.
 * User: sera527
 * Date: 26.10.17
 * Time: 21:40
 */

require __DIR__ . '/vendor/autoload.php';

function cmp($a, $b)
{
    return ($a[1] < $b[1]) ? -1 : 1;
}

$exp = $_POST["expression"] ?: die;
new PZKS\LexicalValidator($exp);
