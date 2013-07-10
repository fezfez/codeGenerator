<?php

namespace CrudGenerator\MetaData\PDO;

use CrudGenerator\MetaData\MetaDataDAO;

/**
 * @CodeGenerator\Description PDO
 */
class PDOMetaDataDAOFactory
{
    /**
     * @param PDOConfig $config
     * @return \CrudGenerator\MetaData\PDO\PDOMetaDataDAO
     */
    public static function getInstance(PDOConfig $config)
    {
        $DSN  = null;
        $type = $config->getType();

        if($type === 'sqlite2') {
            $DSN = $config->getDatabaseName();
        } else {
            $DSN = $config->getType() . ':dbname=' . $config->getDatabaseName() . ';host=' . $config->getHost();
        }

        $pdo = new \PDO(
            $DSN,
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
