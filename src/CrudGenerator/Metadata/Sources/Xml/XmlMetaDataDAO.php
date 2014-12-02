<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Sources\Xml;

use CrudGenerator\Metadata\DataObject\MetaDataCollection;
use CrudGenerator\Metadata\Sources\Json\JsonMetaDataDAO;
use CrudGenerator\Metadata\Sources\MetaDataDAOInterface;

/**
 * Xml adapter
 */
class XmlMetaDataDAO implements MetaDataDAOInterface
{
    /**
     * @var JsonMetaDataDAO
     */
    private $jsonMetaDataDAO = null;

    /**
     * Constructor.
     * @param JsonMetaDataDAO $jsonMetaDataDAO
     */
    public function __construct(JsonMetaDataDAO $jsonMetaDataDAO)
    {
        $this->jsonMetaDataDAO = $jsonMetaDataDAO;
    }

    /**
     * Get all metadata from MySQL
     *
     * @return MetaDataCollection
     */
    public function getAllMetadata()
    {
        return $this->jsonMetaDataDAO->getAllMetadata();
    }

    /**
     * Get particularie metadata from MySQL
     *
     * @param  string                                                      $tableName
     * @return \CrudGenerator\Metadata\Sources\Json\MetadataDataObjectJson
     */
    public function getMetadataFor($tableName, array $parentName = array())
    {
        return $this->jsonMetaDataDAO->getMetadataFor($tableName);
    }
}
