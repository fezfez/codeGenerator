<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Json;

use CrudGenerator\MetaData\Sources\Json\JsonConfig;

$config = new JsonConfig();
$config->setConfigUrl(__DIR__ . '/data.json');

return $config;
