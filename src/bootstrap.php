<?php

use JMikola\ConfigExtension;
use JMikola\FacebookExtension;
use JMikola\Twig\FacebookEventExtension;
use Silex\Application;
use Silex\Extension\TwigExtension;
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

$app->register(new TwigExtension(), array(
    'twig.path' => __DIR__.'/templates',
    'twig.class_path' => __DIR__.'/../vendor/silex/vendor/twig/lib',
    'twig.configure' => $app->protect(function (\Twig_Environment $twig) use ($app) {
        $twig->addExtension(new FacebookEventExtension());
    }),
));

$app->register(new UrlGeneratorExtension());

return $app;
