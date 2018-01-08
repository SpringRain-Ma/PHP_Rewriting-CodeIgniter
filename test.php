<?php

$str = 'http://localhost/index.php/////';
//
////$res = str_replace(array('//', '../'), '/');
//
//$hello  = "Hello World";
//$trimmed = trim($hello, "Hdle");
//var_dump($trimmed);
////echo trim($str, "/p");

$uri = '';

$re = parse_url($uri, PHP_URL_PATH);

var_dump($re);