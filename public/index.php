<?php

define('ROOT', '..');
define('LIB', ROOT.'/lib');
define('VIEW', ROOT.'/lib/View');

require_once ROOT.'/lib/Classloader.php';

$config = (array) require ROOT.'/config/config.php';

$app = new App($config);
$app->start();
