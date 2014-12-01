<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\MetaData\Sources\Json;

use CrudGenerator\MetaData\MetaDataSource;
use JSONSchema\SchemaGeneratorFactory;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\MetaData\Driver\File\Web\WebDriverFactory;
use CrudGenerator\MetaData\Driver\File\FileDriverFactory;
use CrudGenerator\MetaData\Sources\MetaDataDAOFactoryConfigInterface;

/**
 * Create Json Metadata DAO instance
 *
 */
class JsonMetaDataDAOFactory implements MetaDataDAOFactoryConfigInterface
{
    /**
     * @param  DriverConfig                                         $config
     * @throws \InvalidArgumentException
     * @return \CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAO
     */
    public static function getInstance(DriverConfig $config)
    {
        $fileDriver      = FileDriverFactory::getInstance($config);
        $schemaGenerator = SchemaGeneratorFactory::getInstance();

        return new JsonMetaDataDAO(
            $fileDriver,
            $config,
            $schemaGenerator
        );
    }

    /**
     * @param  MetaDataSource $metadataSource
     * @return boolean
     */
    public static function checkDependencies(MetaDataSource $metadataSource)
    {
        return true;
    }

    /**
     * @return \CrudGenerator\MetaData\MetaDataSource
     */
    public static function getDescription()
    {
        $dataObject = new MetaDataSource();
        $dataObject->setDefinition("Json adapter")
                   ->setMetadataDaoFactory('CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory')
                   ->setMetadataDao("CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAO")
                   ->addDriverDescription(\CrudGenerator\MetaData\Driver\File\Web\WebDriverFactory::getDescription())
                   ->setUniqueName('Json');

        return $dataObject;
    }
}
