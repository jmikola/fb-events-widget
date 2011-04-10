<?php

use JMikola\ConfigExtension;
use JMikola\FacebookExtension;
use Silex\Application;
use Silex\Extension\UrlGeneratorExtension;

require_once __DIR__.'/../vendor/silex/autoload.php';

$app = new Application();

$app['autoloader']->registerNamespaces(array(
    'JMikola' => __DIR__,
));

$app->register(new ConfigExtension(), array(
    'config.ini_file' => __DIR__.'/parameters.ini',
));

$app->register(new FacebookExtension(), array(
    'facebook.class_file' => __DIR__.'/../vendor/facebook/src/facebook.php',
));

$app->register(new UrlGeneratorExtension());

return $app;
