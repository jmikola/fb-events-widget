<?php

use JMikola\ConfigExtension;
use JMikola\FacebookExtension;
use JMikola\Twig\FacebookEventExtension;
use Silex\Application;
use Silex\Extension\TwigExtension;
use Silex\Extension\UrlGeneratorExtension;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

$app->error(function (\Exception $e) use ($app) {
    $error = null;

    if ($e instanceof NotFoundHttpException || in_array($app['request']->server->get('REMOTE_ADDR'), array('127.0.0.1', '::1'))) {
        $error = $e->getMessage();
    }

    return new Response(
        $app['twig']->render('error.html.twig', array('error' => $error)),
        $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500
    );
});

return $app;
