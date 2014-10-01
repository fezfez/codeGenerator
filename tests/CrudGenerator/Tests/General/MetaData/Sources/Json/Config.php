<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\Json;

use CrudGenerator\MetaData\Sources\Json\JsonConfig;
use CrudGenerator\MetaData\Connector\WebConnectorFactory;
use CrudGenerator\MetaData\Connector\WebConnectorConfig;

$connectorConfig = new WebConnectorConfig();
$connectorConfig->setConfigUrl(__DIR__ . '/data.json');

$config = new JsonConfig();
$config->setConnector(WebConnectorFactory::getInstance())
       ->setConnectorConfig($connectorConfig)

return $config;
