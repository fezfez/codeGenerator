<?php
namespace CrudGenerator\Tests\MetaData\PDO;

use CrudGenerator\MetaData\PDO\PDOConfig as RealPDOConfig;

class PDOConfig
{
    public static function getConfig()
    {
        $pdoConfig = new RealPDOConfig();
        $pdoConfig->setDatabaseName('sqlite2::memory:')
                  ->setType('sqlite2')
                  ->setPassword(null)
                  ->setUser(null);

        return $pdoConfig;
    }
}

