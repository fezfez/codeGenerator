<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\MySQL;

use CrudGenerator\MetaData\Sources\MySQL\MySQLConfig;

$pdoConfig = new MySQLConfig();
$pdoConfig->setDatabaseName('code_generator')
->setPassword('')
->setUser('travis')
->setPort('3302')
->setHost('localhost');

return $pdoConfig;
