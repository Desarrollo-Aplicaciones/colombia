<?php
#!/bin/usr/php


if ($argc < 3) die('\r\nUsage: perl conn_bd_images.pl php --> my $output = `php /var/www/test.farmalisto.com.co/perl.php key1 value1 key2 value2 key3 value3` ');

//echo "\r\n dir:".
$myDir=$argv[1];
//echo "\r\n token:".
$myToken=$argv[2];
//echo "\r\n imagen:".
$myImage=$argv[3];
//echo "\r\n product:".
$myProduct=$argv[4];

if (!chdir($myDir)) die($myDir . ' is not a valid directory!'); 

define('_PS_ADMIN_DIR_', getcwd());
require(dirname(__FILE__).'/../config/config.inc.php');
Context::getContext()->shop->setContext(Shop::CONTEXT_ALL);

if (substr(_COOKIE_KEY_, 34, 8) != $myToken)
die ("bad token");


ini_set('max_execution_time', 7200);

$ic = new AdminImagesController;
$ic->_regenerateThumbnails_bash('products', true, $myImage, $myProduct);

?>