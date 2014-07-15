<?php
namespace CrudGenerator\Tests\PostgreSQL\MetaData\PostgreSQL\PostgreSQL;

use CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLConfig;

$pdoConfig = new PostgreSQLConfig();
$pdoConfig->setDatabaseName('code_generator')
->setPassword('')
->setUser('postgres')
->setPort('5432')
->setHost('localhost');

return $pdoConfig;
