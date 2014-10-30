<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Json;

use CrudGenerator\MetaData\Driver\DriverConfig;

$config = new DriverConfig("test");
$config->setDriver("CrudGenerator\MetaData\Driver\File\Web\WebDriverFactory");
$config->response('configUrl', __DIR__ . '/dataWithNoColumnInFirstLevel.json');

return $config;
