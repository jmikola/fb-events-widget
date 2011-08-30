<?php

use Fbew\ConfigExtension;
use Fbew\FacebookExtension;
use Fbew\Twig\FacebookEventExtension;
use Silex\Application;
use Silex\Extension\TwigExtension;
use Silex\Extension\UrlGeneratorExtension;
use Symfony\Component\HttpFoundation\Response;

$app = new Application();

$app->register(new ConfigExtension(), array(
    'config.ini_file' => __DIR__.'/parameters.ini',
));

$app->register(new FacebookExtension(), array(
    'facebook.class_file' => __DIR__.'/../vendor/facebook/src/facebook.php',
));

$app->register(new TwigExtension(), array(
    'twig.cache.path' => __DIR__.'/cache/twig',
    'twig.path'       => __DIR__.'/templates',
    'twig.configure'  => $app->protect(function (\Twig_Environment $twig) use ($app) {
        $twig->addExtension(new FacebookEventExtension());
        $twig->setCache($app['twig.cache.path']);
    }),
));

$app->register(new UrlGeneratorExtension());

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $error = 404 == $code ? $e->getMessage() : null;

    return new Response($app['twig']->render('error.html.twig', array('error' => $error)), $code);
});

return $app;
