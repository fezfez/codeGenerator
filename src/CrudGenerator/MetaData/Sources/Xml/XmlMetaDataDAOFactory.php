<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\MetaData\Sources\Xml;

use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\MetaData\Driver\File\FileDriverFactory;
use CrudGenerator\MetaData\Sources\MetaDataDAOFactoryConfigInterface;
use CrudGenerator\Utils\Installer;

/**
 * Create Xml Metadata DAO instance
 *
 */
class XmlMetaDataDAOFactory implements MetaDataDAOFactoryConfigInterface
{
    const TMP_PATH = 'tmp/';
    const FILE_EXTENSION = '-from-xml.json';

    /**
     * @param  DriverConfig              $config
     * @throws \InvalidArgumentException
     * @return XmlMetaDataDAO
     */
    public static function getInstance(DriverConfig $config)
    {
        $fileDriver = FileDriverFactory::getInstance($config);

        $json = json_encode(
            simplexml_load_string(
                $fileDriver->getFile($config)
            )
        );

        $fileName = md5($config->getResponse('configUrl')).self::FILE_EXTENSION;
        $filePath = Installer::getDirectory(Installer::TMP).$fileName;

        file_put_contents($filePath, $json);

        $jsonConfig = clone $config;
        $jsonConfig->response('configUrl', $filePath);
        $jsonConfig->setDriver('CrudGenerator\MetaData\Driver\File\Web\WebDriverFactory');

        return new XmlMetaDataDAO(JsonMetaDataDAOFactory::getInstance($jsonConfig));
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
        $dataObject->setDefinition("Xml")
                   ->setMetadataDaoFactory('CrudGenerator\MetaData\Sources\Xml\XmlMetaDataDAOFactory')
                   ->setMetadataDao("CrudGenerator\MetaData\Sources\Xml\XmlMetaDataDAO")
                   ->addDriverDescription(\CrudGenerator\MetaData\Driver\File\Web\WebDriverFactory::getDescription())
                   ->setUniqueName('Xml');

        return $dataObject;
    }
}
