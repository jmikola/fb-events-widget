<?php

require_once __DIR__.'/../vendor/silex/vendor/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'JMikola' => __DIR__,
    'Symfony' => __DIR__.'/../vendor/silex/vendor',
    'Silex'   => __DIR__.'/../vendor/silex/src',
));
$loader->registerPrefixes(array(
    'Pimple' => __DIR__.'/../vendor/silex/vendor/pimple/lib',
    'Twig_'  => __DIR__.'/../vendor/silex/vendor/twig/lib',
));
$loader->register();
