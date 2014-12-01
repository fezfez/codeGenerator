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

use CrudGenerator\MetaData\Sources\MetaDataDAOInterface;
use CrudGenerator\MetaData\DataObject\MetaDataCollection;
use CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAO;

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
     * @param  string                                                        $tableName
     * @return \CrudGenerator\MetaData\Sources\MySQL\MetadataDataObjectMySQL
     */
    public function getMetadataFor($tableName, array $parentName = array())
    {
        return $this->jsonMetaDataDAO->getMetadataFor($tableName);
    }
}
