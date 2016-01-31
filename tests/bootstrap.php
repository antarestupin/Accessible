<?php

/*
 * This file bootstraps the test environment.
 */
error_reporting(E_ALL | E_STRICT);

spl_autoload_register(function($class)
{
    if (0 === strpos($class, 'Accessible\\Tests\\')) {
        $path = __DIR__.'/'.strtr($class, '\\', '/').'.php';
        if (is_file($path) && is_readable($path)) {
            require_once $path;
            return true;
        }
    }
});

$loader = require __DIR__ . '/../vendor/autoload.php';
Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
