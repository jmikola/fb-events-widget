<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app = require __DIR__.'/bootstrap.php';

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

$app->get('/', function() use ($app) {
    return $app['twig']->render('index.html.twig', array(
        'facebookAppId' => $app['facebook.app.id'],
    ));
});

$app->get('/{creatorId}', function($creatorId) use ($app) {
    $fql = sprintf('
        SELECT eid, name, host, description, start_time, end_time, location, venue
        FROM event
        WHERE
            eid IN (SELECT eid FROM event_member WHERE uid=%1$s)
            AND creator=%1$s
            AND start_time > now()
        ORDER BY start_time ASC',
        $creatorId
    );

    $events = $app['facebook']->api(array(
        'method' => 'fql.query',
        'query' => $fql,
    ));

    return $app['twig']->render('widget_events.html.twig', array(
        'events' => $events,
    ));
})->assert('creatorId', '\d+');

return $app;
