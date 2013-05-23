<?php

namespace CrudGenerator\MetaData\PDO;

use CrudGenerator\MetaData\MetaDataDAO;

/**
 * @CodeGenerator\Description PDO
 */
class PDOMetaDataDAOFactory
{
    private function __construct()
    {

    }

    /**
     * @return PDOMetaDataDAO
     */
    public static function getInstance(PDOConfig $config)
    {
        $pdo = new \PDO(
            'pgsql:dbname=' . $config->getDatabaseName() . ';host=' . $config->getHost(),
            $config->getUser(),
            $config->getPassword()
        );

        return new PDOMetaDataDAO($pdo);
    }

    public static function checkDependencies()
    {
        return true;
    }
}
