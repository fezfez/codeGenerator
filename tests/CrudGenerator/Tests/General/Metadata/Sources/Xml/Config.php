<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Xml;

use CrudGenerator\Metadata\Driver\DriverConfig;

$config = new DriverConfig("test");
$config->setDriver("CrudGenerator\Metadata\Driver\File\Web\WebDriverFactory");
$config->response('configUrl', __DIR__.'/data.xml');

return $config;
