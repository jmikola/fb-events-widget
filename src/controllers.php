<?php

$app->get('/', function() use ($app) {
    return $app['twig']->render('index.html.twig', array(
        'facebookAppId' => $app['facebook.app.id'],
    ));
});

$app->get('/{profileId}', function($profileId) use ($app) {
    $fqlEvent = sprintf('
        SELECT eid, name, host, description, start_time, end_time, location, venue
        FROM event
        WHERE
            eid IN (SELECT eid FROM event_member WHERE uid=%1$s)
            AND creator=%1$s
            AND start_time > now()
        ORDER BY start_time ASC',
        $profileId
    );

    $fqlProfile = sprintf('
        SELECT id, name, url, type, username
        FROM profile
        WHERE id = %s',
        $profileId
    );

    $result = $app['facebook']->api(array(
        'method' => 'fql.multiquery',
        'queries' => array(
            'events'  => $fqlEvent,
            'profile' => $fqlProfile,
        ),
    ));

    $events = isset($result[0]['fql_result_set']) ? $result[0]['fql_result_set'] : array();
    $profile = isset($result[1]['fql_result_set'][0]) ? $result[1]['fql_result_set'][0] : null;

    $timezone = new \DateTimeZone(ini_get('date.timezone'));

    return $app['twig']->render('widget_events.html.twig', array(
        'events'   => $events,
        'profile'  => $profile,
        'timezone' => $timezone,
    ));
})->assert('profileId', '\d+');
