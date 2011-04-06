<?php

require_once __DIR__.'/../vendor/silex/autoload.php';

use Silex\Application;

$app = new Application();

require_once __DIR__.'/bootstrap.php';

$app->get('/', function(){});

$app->run();
