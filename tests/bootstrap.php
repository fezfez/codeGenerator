<?php
/*
 * This file bootstraps the test environment.
 */
namespace CrudGenerator\Tests;

use CrudGenerator\Utils\Installer;
use CrudGenerator\Utils\FileManager;

error_reporting(-1);
ini_set('memory_limit', '512M');

$vendorDir = __DIR__ . '/../vendor';

if (false === is_file($vendorDir . '/autoload.php')) {
    throw new \Exception("You must set up the project dependencies, run the following commands:
                    wget http://getcomposer.org/composer.phar
                    php composer.phar install
                    ");
} else {
    include($vendorDir . '/autoload.php');
}

// register silently failing autoloader
spl_autoload_register(function($class) {
    if (0 === strpos($class, 'CrudGenerator\Tests\\')) {
        $path = __DIR__ . '/' . strtr($class, '\\', '/') . '.php';
        if (is_file($path) === true && is_readable($path) === true) {
            require_once $path;

            return true;
        }
    } elseif(0 === strpos($class, 'TestZf2\\')) {
        $path = __DIR__ . '/CrudGenerator/Tests/ZF2/module/' . strtr($class, '\\', '/') . '.php';

        if (is_file($path) === true && is_readable($path) === true) {
            require_once $path;

            return true;
        }
    }
});

Installer::install(new FileManager());
