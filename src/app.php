<?php

use Fbew\ConfigServiceProvider;
use Fbew\FacebookServiceProvider;
use Fbew\Twig\FacebookEventExtension;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\HttpFoundation\Response;

$app = new Application();

$app->register(new ConfigServiceProvider(), array(
    'config.ini_file' => __DIR__.'/parameters.ini',
));

$app->register(new FacebookServiceProvider(), array(
    'facebook.class_file' => __DIR__.'/../vendor/facebook/src/facebook.php',
));

$app->register(new TwigServiceProvider(), array(
    'twig.cache.path' => __DIR__.'/../cache',
    'twig.path'       => __DIR__.'/views',
    'twig.configure'  => $app->protect(function (\Twig_Environment $twig) use ($app) {
        $twig->addExtension(new FacebookEventExtension());
        $twig->setCache($app['twig.cache.path']);
    }),
));

$app->register(new UrlGeneratorServiceProvider());

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $error = 404 == $code ? $e->getMessage() : null;

    return new Response($app['twig']->render('error.html.twig', array('error' => $error)), $code);
});

return $app;
