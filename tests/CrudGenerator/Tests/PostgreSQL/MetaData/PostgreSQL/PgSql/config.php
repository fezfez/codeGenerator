<?php
namespace CrudGenerator\Tests\PostgreSQL\MetaData\PostgreSQL\PostgreSQL;

use CrudGenerator\MetaData\Driver\DriverConfig;

$config = new DriverConfig('Web');
$config->addQuestion('Database Name', 'configDatabaseName');
$config->addQuestion('Host', 'configHost');
$config->addQuestion('User', 'configUser');
$config->addQuestion('Password', 'configPassword');
$config->addQuestion('Port', 'configPort');

$config->response('configDatabaseName', 'code_generator');
$config->response('configHost', 'localhost');
$config->response('configUser', 'postgres');
$config->response('configPort', '5432');
$config->response('dsn', \CrudGenerator\MetaData\Driver\Pdo\PdoDriver::POSTGRESQL);

return $config;