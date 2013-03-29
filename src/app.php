<?php

use Fbew\FacebookServiceProvider;
use Fbew\Twig\FacebookEventExtension;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Symfony\Component\HttpFoundation\Response;

$loader = require_once __DIR__.'/../vendor/autoload.php';

$app = new Application();

require_once is_file(__DIR__.'/config.php')
    ? __DIR__.'/config.php'
    : __DIR__.'/config.php.dist';

$app->register(new FacebookServiceProvider());
$app->register(new UrlGeneratorServiceProvider());
$app->register(new TwigServiceProvider(), [
    'twig.options' => ['cache' => $app['twig.cache_dir']],
    'twig.path'    => __DIR__.'/views',
]);

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addExtension(new FacebookEventExtension());

    return $twig;
}));

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $error = 404 == $code ? $e->getMessage() : null;

    return new Response($app['twig']->render('error.html.twig', ['error' => $error]), $code);
});

require_once __DIR__.'/controllers.php';

return $app;
