<?php

namespace CrudGenerator\MetaData\PDO;

use CrudGenerator\MetaData\MetaDataDAO;
use CrudGenerator\MetaData\PDO\SqlManager;

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
        $type = $config->getType();

        if($type === 'sqlite2') {
            $pdo = new \PDO($config->getDatabaseName());
        } else {
            $DSN = $config->getType() . ':dbname=' . $config->getDatabaseName() . ';host=' . $config->getHost();
            $pdo = new \PDO(
                $DSN,
                $config->getUser(),
                $config->getPassword()
            );
        }

        return new PDOMetaDataDAO(
            $pdo,
            $config,
            new SqlManager()
        );
    }

    public static function checkDependencies()
    {
        return true;
    }
}
