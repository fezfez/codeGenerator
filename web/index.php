<?php

require_once __DIR__.'/../vendor/autoload.php';

chdir(__DIR__.'/../');

$app = new Silex\Application();

require __DIR__.'/../silex/resources/config/prod.php';
require __DIR__.'/../silex/src/app.php';

require __DIR__.'/../silex/src/controllers.php';

$app->run();