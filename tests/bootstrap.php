<?php
/*
 * This file bootstraps the test environment.
 */
namespace CrudGenerator\Tests;

error_reporting(-1);
ini_set('memory_limit', '512M');

$vendorDir = __DIR__ . '/../vendor';

if (!@include($vendorDir . '/autoload.php')) {
    die("You must set up the project dependencies, run the following commands:
                    wget http://getcomposer.org/composer.phar
                    php composer.phar install
                    ");
}

// register silently failing autoloader
spl_autoload_register(function($class) {
    if (0 === strpos($class, 'CrudGenerator\Tests\\')) {
        $path = __DIR__.'/'.strtr($class, '\\', '/').'.php';
        if (is_file($path) && is_readable($path)) {
            require_once $path;

            return true;
        }
    } elseif(0 === strpos($class, 'TestZf2\\')) {
        $path = __DIR__.'/CrudGenerator/Tests/ZF2/module/'.strtr($class, '\\', '/').'.php';

        if (is_file($path) && is_readable($path)) {
            require_once $path;

            return true;
        }
    }
});
