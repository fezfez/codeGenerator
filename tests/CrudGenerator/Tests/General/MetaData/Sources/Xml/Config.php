<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Xml;

use CrudGenerator\MetaData\Driver\DriverConfig;

$config = new DriverConfig("test");
$config->setDriver("CrudGenerator\MetaData\Driver\File\Web\WebDriverFactory");
$config->response('configUrl', __DIR__ . '/data.xml');

return $config;
