<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

$app->get('/', function() use ($app) {
    return $app['twig']->render('index.html.twig');
});

$app->get('/auth/facebook', function() use ($app) {
    $loginUrl = $app['facebook']->getLoginUrl(array(
        'redirect_uri' => $app['url_generator']->generate('widgets', array('from_fb' => 1), true),
        'scope'        => implode(',', $app['facebook.require_perms']),
    ));
    return $app->redirect($loginUrl);
})->bind('login');

$app->get('/widgets', function(Request $request) use ($app) {
    if ($request->get('from_fb')) {
        throw new AccessDeniedHttpException('We need certain Facebook permissions to continue');
    }

    if (0 === (string) $app['facebook']->getUser()) {
        return $app->redirect($app['url_generator']->generate('login'));
    }

    $result = $app['facebook']->api('/me/permissions');

    foreach ($app['facebook.require_perms'] as $perm) {
        if (empty($result['data'][0][$perm])) {
            return $app->redirect($app['url_generator']->generate('login'));
        }
    }

    var_dump('Success!');
})->bind('widgets');

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

    foreach ($events as &$event) {
        $event['start_time'] = $app['facebook.normalizeEventTimestamp']($event['start_time'], $timezone);
        $event['end_time'] = $app['facebook.normalizeEventTimestamp']($event['end_time'], $timezone);
    }

    return $app['twig']->render('widget_events.html.twig', array(
        'events'   => $events,
        'profile'  => $profile,
        'timezone' => $timezone,
    ));
})->assert('creatorId', '\d+');
