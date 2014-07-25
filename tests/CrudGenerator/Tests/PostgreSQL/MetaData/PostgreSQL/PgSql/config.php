<?php
namespace CrudGenerator\Tests\PostgreSQL\MetaData\PostgreSQL\PostgreSQL;

use CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLConfig;

$pdoConfig = new PostgreSQLConfig();
$pdoConfig->setConfigDatabaseName('code_generator')
->setConfigPassword('')
->setConfigUser('postgres')
->setConfigPort('5432')
->setConfigHost('localhost');

return $pdoConfig;
