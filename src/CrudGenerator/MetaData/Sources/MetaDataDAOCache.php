<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\MetaData\Sources;

use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\Installer;

/**
 * Metadata DAO cache
 *
 * @author Stéphane Demonchaux
 */
class MetaDataDAOCache implements MetaDataDAOInterface
{
    /**
     * @var MetaDataDAOInterface
     */
    private $metadataDAO = null;
    /**
     * @var FileManager
     */
    private $fileManager = null;
    /**
     * @var DriverConfig
     */
    private $config = null;
    /**
     * @var boolean
     */
    private $noCache = null;

    /**
     * @param MetaDataDAOInterface $metadataDAO
     * @param FileManager $fileManager
     * @param DriverConfig $config
     * @param boolean $noCache
     */
    public function __construct(
        MetaDataDAOInterface $metadataDAO,
        FileManager $fileManager,
        DriverConfig $config = null,
        $noCache = false
    ) {
        $this->metadataDAO = $metadataDAO;
        $this->fileManager = $fileManager;
        $this->config      = $config;
        $this->noCache     = $noCache;
    }
    /**
     * Get all metadata from the concrete metadata DAO
     *
     * @return \CrudGenerator\MetaData\DataObject\MetaDataCollection
     */
    public function getAllMetadata()
    {
        $configName     = ($this->config !== null) ? $this->config->getUniqueName() : '';
        $cacheFilename  = Installer::BASE_PATH . Installer::CACHE_PATH . DIRECTORY_SEPARATOR;
        $cacheFilename .= md5('all_metadata' . get_class($this->metadataDAO) . $configName);

        if ($this->fileManager->isFile($cacheFilename) === true && $this->noCache === false) {
            $data = unserialize($this->fileManager->fileGetContent($cacheFilename));
        } else {
            $data = $this->metadataDAO->getAllMetadata();
            $this->fileManager->filePutsContent($cacheFilename, serialize($data));
        }

        return $data;
    }

    /**
     * Get particularie metadata from the concrete metadata DAO
     *
     * @param string $entityName
     * @return \CrudGenerator\MetaData\DataObject\MetaData
     */
    public function getMetadataFor($entityName, array $parentName = array())
    {
        $configName     = ($this->config !== null) ? $this->config->getUniqueName() : '';
        $cacheFilename  = Installer::BASE_PATH . Installer::CACHE_PATH . DIRECTORY_SEPARATOR;
        $cacheFilename .= md5('metadata' . $entityName . get_class($this->metadataDAO) . $configName);

        if ($this->fileManager->isFile($cacheFilename) === true && $this->noCache === false) {
            $data = unserialize($this->fileManager->fileGetContent($cacheFilename));
        } else {
            $data = $this->metadataDAO->getMetadataFor($entityName, $parentName);
            $this->fileManager->filePutsContent($cacheFilename, serialize($data));
        }

        return $data;
    }
}
