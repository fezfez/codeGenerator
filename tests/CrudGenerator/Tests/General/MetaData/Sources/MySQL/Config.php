<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\MySQL;

use CrudGenerator\MetaData\Sources\MySQL\MySQLConfig;

$pdoConfig = new MySQLConfig();
$pdoConfig->setConfigDatabaseName('code_generator')
->setConfigPassword('')
->setConfigUser('travis')
->setConfigPort('3302')
->setConfigHost('localhost');

return $pdoConfig;
