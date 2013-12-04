<?php

$class_loader_basedir = dirname(dirname(__FILE__)); // __DIR__ doesn't exist in PHP 5.2
require_once $class_loader_basedir . '/src/Acquia/Common/ClassLoader.php';
$app_class_loader = new Acquia_Common_ClassLoader('Acquia', 'src');
$app_class_loader->register();

$test_class_loader = new Acquia_Common_ClassLoader('Acquia_Test', 'test');
$test_class_loader->register();
