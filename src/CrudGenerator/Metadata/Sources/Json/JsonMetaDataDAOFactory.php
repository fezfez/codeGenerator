<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Sources\Json;

use CrudGenerator\Metadata\MetaDataSource;
use JSONSchema\SchemaGeneratorFactory;
use CrudGenerator\Metadata\Driver\DriverConfig;
use CrudGenerator\Metadata\Driver\File\Web\WebDriverFactory;
use CrudGenerator\Metadata\Driver\File\FileDriverFactory;
use CrudGenerator\Metadata\Sources\MetaDataDAOFactoryConfigInterface;

/**
 * Create Json Metadata DAO instance
 *
 */
class JsonMetaDataDAOFactory implements MetaDataDAOFactoryConfigInterface
{
    /**
     * @param  DriverConfig                                         $config
     * @throws \InvalidArgumentException
     * @return \CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAO
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
     * @return \CrudGenerator\Metadata\MetaDataSource
     */
    public static function getDescription()
    {
        $dataObject = new MetaDataSource();
        $dataObject->setDefinition("Json adapter")
                   ->setMetadataDaoFactory('CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAOFactory')
                   ->setMetadataDao("CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAO")
                   ->addDriverDescription(\CrudGenerator\Metadata\Driver\File\Web\WebDriverFactory::getDescription())
                   ->setUniqueName('Json');

        return $dataObject;
    }
}
