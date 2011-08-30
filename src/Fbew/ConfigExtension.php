<?php

namespace Fbew;

use Silex\Application;
use Silex\ExtensionInterface;

class ConfigExtension implements ExtensionInterface
{
    public function register(Application $app)
    {
        if (isset($app['config.ini_file'])) {
            if (false === ($config = parse_ini_file($app['config.ini_file']))) {
                throw new \RuntimeException('Failed to parse INI file: ' . $app['config.ini_file']);
            }

            foreach ($config as $name => $value) {
                $app[$name] = $value;
            }
        }
    }
}
