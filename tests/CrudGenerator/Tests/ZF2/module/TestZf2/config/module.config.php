<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'doctrine' => array(
        'configuration' => array(
            'proxy_dir'         => __DIR__ . '/../../../data/Doctrine/Proxy',
        ),
        'driver' => array(
            'orm_default' => array(
                'class'   => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                 'paths' => array(
                     realpath(__DIR__ . '/../Entities/')
                 )
            )
        ),
        'connection' => array(
            // default connection name
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOPgSql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '5432',
                    'user'     => 'postgres',
                    'password' => '',
                    'dbname'   => 'code_generator_test',
                ),
                'doctrine_type_mappings' => array(
                    'enum' => 'string'
                )
            )
        ),
    ),
);
