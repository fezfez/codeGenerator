<?php

if (!(@include_once __DIR__ . '/../vendor/autoload.php') && !(@include_once __DIR__ . '/../../../autoload.php')) {
    throw new RuntimeException('Error: vendor/autoload.php could not be found. Did you run php composer.phar install?');
}

if (is_file(__DIR__ . '/../vendor/autoload.php')) {
	chdir(__DIR__ . '/../');
}

$app = new Silex\Application();

require __DIR__.'/../silex/resources/config/prod.php';
require __DIR__.'/../silex/src/app.php';

require __DIR__.'/../silex/src/controllers.php';


$app->run();