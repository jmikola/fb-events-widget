<?php

namespace Fbew;

use Silex\Application;
use Silex\ServiceProviderInterface;

class FacebookServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['facebook'] = $app->share(function() use ($app) {
            return new \Facebook(array(
                'appId'      => $app['facebook.app.id'],
                'secret'     => $app['facebook.app.secret'],
                'cookie'     => isset($app['facebook.app.cookie']) ? $app['facebook.app.cookie'] : null,
                'domain'     => isset($app['facebook.app.domain']) ? $app['facebook.app.domain'] : null,
                'fileUpload' => isset($app['facebook.app.fileUpload']) ? $app['facebook.app.fileUpload'] : null,
            ));
        });

        if (isset($app['facebook.class_file'])) {
            spl_autoload_register(function($class) use ($app) {
                if ('\\' === $class[0]) {
                    $class = substr($class, 1);
                }

                if ('Facebook' === $class) {
                    require_once $app['facebook.class_file'];
                }
            });
        }
    }
}
