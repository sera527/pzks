<?php
/**
 * Created by PhpStorm.
 * User: sera527
 * Date: 08.11.17
 * Time: 0:41
 */
require __DIR__ . '/vendor/autoload.php';

$exp = $_GET["expression"] ?: die;
new PZKS\SyntaxAnalyser($exp);