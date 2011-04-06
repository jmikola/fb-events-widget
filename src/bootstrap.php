<?php

use JMikola\ConfigExtension;
use JMikola\FacebookExtension;
use Silex\Extension\UrlGeneratorExtension;

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
