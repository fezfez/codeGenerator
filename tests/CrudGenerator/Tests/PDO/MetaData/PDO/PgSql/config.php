<?php
namespace CrudGenerator\Tests\PDO\MetaData\PDO\PgSql;

use CrudGenerator\MetaData\PDO\PDOConfig;

$pdoConfig = new PDOConfig();
$pdoConfig->setDatabaseName('code_generator')
          ->setType('pgsql')
          ->setPassword('bro123')
          ->setUser('brotte')
          ->setPort('5432')
          ->setHost('localhost');

return $pdoConfig;
