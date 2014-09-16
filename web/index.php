<?php

set_time_limit(0);
error_reporting(-1);
ini_set('memory_limit', '1G');

if (is_file(__DIR__ . '/../vendor/autoload.php') === true) {
    chdir(__DIR__ . '/../'); // standalone
} elseif (is_file(__DIR__ . '/../../../autoload.php') === true) {
    chdir(__DIR__ . '/../../../../'); // install with composer
} else {
    throw new RuntimeException('Error: vendor/autoload.php could not be found. Did you run php composer.phar install?');
}

include_once 'vendor/autoload.php';

Symfony\Component\Debug\Debug::enable();

$app = new Silex\Application();

require __DIR__.'/../silex/resources/config/prod.php';
require __DIR__.'/../silex/src/app.php';
require __DIR__.'/../silex/src/controllers.php';

$app->run();
