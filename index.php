<?php

ini_set('display_errors', 0);

require_once __DIR__.'/project/vendor/autoload.php';

$app = require __DIR__.'/project/src/app.php';
require __DIR__.'/project/config/prod.php';
require __DIR__.'/project/src/functions.php';
require __DIR__.'/project/src/controllers.php';
$app->run();
