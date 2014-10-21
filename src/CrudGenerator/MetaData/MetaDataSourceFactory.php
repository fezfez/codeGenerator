<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\MetaData;

use CrudGenerator\MetaData\Sources\MetaDataDAOCache;
use CrudGenerator\Utils\Installer;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\Utils\FileManager;

/**
 * MetaData source factory
 * @author Stéphane Demonchaux
 */
class MetaDataSourceFactory
{
    /**
     * @param string $metadataSourceFactoryName
     * @param DriverConfig $config
     * @param boolean $noCache
     * @return \CrudGenerator\MetaData\Sources\MetaDataDAOCache
     */
    public function create($metadataSourceFactoryName, DriverConfig $config = null, $noCache = false)
    {
        if (null !== $config) {
            $metadataSource = $metadataSourceFactoryName::getInstance($config);
        } else {
            $metadataSource = $metadataSourceFactoryName::getInstance();
        }

        return new MetaDataDAOCache(
            $metadataSource,
            new FileManager(),
            $config,
            $noCache
        );
    }
}
