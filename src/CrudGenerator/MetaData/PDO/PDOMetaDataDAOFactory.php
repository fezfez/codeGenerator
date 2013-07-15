<?php

namespace CrudGenerator\MetaData\PDO;

use CrudGenerator\MetaData\PDO\SqlManager;

/**
 * Create PDO Metadata DAO instance
 *
 * @CodeGenerator\Description PDO
 */
class PDOMetaDataDAOFactory
{
    /**
     * Create PDO Metadata DAO instance
     *
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
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return new PDOMetaDataDAO(
            $pdo,
            $config,
            new SqlManager()
        );
    }

    /**
     * Check if dependence are complete
     * @return boolean
     */
    public static function checkDependencies()
    {
        return true;
    }
}
