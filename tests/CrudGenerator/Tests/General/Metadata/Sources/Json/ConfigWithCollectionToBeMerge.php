<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Json;

use CrudGenerator\Metadata\Driver\DriverConfig;

$config = new DriverConfig("test");
$config->setDriver("CrudGenerator\Metadata\Driver\File\Web\WebDriverFactory");
$config->response('configUrl', __DIR__ . '/dataWithCollectionToBeMerge.json');

return $config;
