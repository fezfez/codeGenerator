<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\MetaData\Driver\Pdo;

use CrudGenerator\MetaData\Config\ConfigException;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\MetaData\Driver\DriverInterface;

/**
 * Json configuration for Json Metadata adapter
 *
 * @author Stéphane Demonchaux
 */
class PdoDriver implements DriverInterface
{
    /**
     * @var string
     */
    const MYSQL = 'MySql';
    /**
     * @var string
     */
    const POSTGRESQL = 'PostgreSQL';
    /**
     * @var string
     */
    const ORACLE = 'Oracle';

    /**
     * @param  DriverConfig    $config
     * @throws ConfigException
     * @return \PDO
     */
    public function getConnection(DriverConfig $config)
    {
        if ($config->getResponse('configHost') === null || $config->getResponse('configDatabaseName') === null) {
            throw new ConfigException('Empty connection');
        }

        try {
            return new \PDO(
                $this->retrieveDsn($config),
                $config->getResponse('configUser'),
                $config->getResponse('configPassword'),
                array(
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                )
            );
        } catch (\RuntimeException $e) {
            throw new ConfigException($e->getMessage());
        }
    }

    /**
     * @param  DriverConfig $config
     * @throws \Exception
     * @return string
     */
    private function retrieveDsn(DriverConfig $config)
    {
        if ($config->getResponse('dsn') === self::MYSQL) {
            $dsn = 'mysql:host='.$config->getResponse('configHost').';dbname=';
        } elseif ($config->getResponse('dsn') === self::POSTGRESQL) {
            $dsn = 'pgsql:host='.$config->getResponse('configHost').';dbname=';
        } elseif ($config->getResponse('dsn') === self::ORACLE) {
            $dsn = '//'.$config->getResponse('configHost').'/';
        } else {
            throw new \Exception('Dsn not found');
        }

        return $dsn.$config->getResponse('configDatabaseName');
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Driver\DriverInterface::isValid()
     */
    public function isValid(DriverConfig $driverConfig)
    {
        $this->getConnection($driverConfig);

        return true;
    }
}
