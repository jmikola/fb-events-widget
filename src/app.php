<?php

$app = require __DIR__.'/bootstrap.php';

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
            eid IN (SELECT eid FROM event_member WHERE uid=%1$u)
            AND creator=%1$u
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
});

return $app;
