<?php
namespace CrudGenerator\Tests\PDO\MetaData\PDO\PgSql;

use CrudGenerator\MetaData\Sources\PDO\PDOConfig;

$pdoConfig = new PDOConfig();
$pdoConfig->setDatabaseName('code_generator')
->setType('pgsql')
->setPassword('fezfez')
->setUser('steph')
->setPort('5432')
->setHost('localhost');


return $pdoConfig;