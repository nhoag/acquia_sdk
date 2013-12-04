<?php

require_once __DIR__ . '/../src/Acquia/Common/ClassLoader.php';
$app_class_loader = new Acquia_Common_ClassLoader('Acquia', __DIR__ . '/../src');
$app_class_loader->register();

$test_class_loader = new Acquia_Common_ClassLoader('Acquia_Test', 'test');
$test_class_loader->register();
