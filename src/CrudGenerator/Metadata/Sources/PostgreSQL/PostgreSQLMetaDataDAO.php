<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Sources\PostgreSQL;

use CrudGenerator\Metadata\DataObject\MetaDataCollection;
use CrudGenerator\Metadata\DataObject\MetaDataColumn;
use CrudGenerator\Metadata\DataObject\MetaDataColumnCollection;
use CrudGenerator\Metadata\DataObject\MetaDataRelationCollection;
use CrudGenerator\Metadata\Driver\DriverConfig;
use CrudGenerator\Metadata\Sources\MetaDataDAOInterface;
use PDO;

/**
 * PostgreSQL adapter
 */
class PostgreSQLMetaDataDAO implements MetaDataDAOInterface
{
    /**
     * @var array
     */
    private $typeConversion = array(
        'character varying' => 'text',
    );
    /**
     * Pdo stmt
     *
     * @var PDO
     */
    private $pdo = null;
    /**
     * Pdo configuration
     *
     * @var DriverConfig
     */
    private $pdoConfig = null;
    /**
     * @var SqlManager Sql manager
     */
    private $sqlManager = null;

    /**
     * PostgreSQL adapter
     * @param PDO          $pdo
     * @param DriverConfig $pdoConfig
     * @param SqlManager   $sqlManager
     */
    public function __construct(PDO $pdo, DriverConfig $pdoConfig, SqlManager $sqlManager)
    {
        $this->pdo        = $pdo;
        $this->pdoConfig  = $pdoConfig;
        $this->sqlManager = $sqlManager;
    }

    /**
     * Get all metadata from PostgreSQL
     *
     * @return MetaDataCollection
     */
    public function getAllMetadata()
    {
        $sth = $this->pdo->prepare($this->sqlManager->getAllMetadata());

        $sth->execute();

        return $this->pdoMetadataToGeneratorMetadata(
            $sth->fetchAll(
                PDO::FETCH_COLUMN,
                0
            )
        );
    }

    /**
     * Get particularie metadata from PDO
     *
     * @param  string                                                                  $tableName
     * @return \CrudGenerator\Metadata\Sources\PostgreSQL\MetadataDataObjectPostgreSQL
     */
    public function getMetadataFor($tableName, array $parentName = array())
    {
        return $this->hydrateDataObject($tableName);
    }

    /**
     * Convert PostgreSQL mapping to CrudGenerator mapping
     * @param  array                                                 $metadataCollection
     * @return \CrudGenerator\Metadata\DataObject\MetaDataCollection
     */
    private function pdoMetadataToGeneratorMetadata(array $metadataCollection)
    {
        $metaDataCollection = new MetaDataCollection();

        foreach ($metadataCollection as $tableName) {
            $metaDataCollection->append(
                $this->hydrateDataObject($tableName)
            );
        }

        return $metaDataCollection;
    }

    /**
     * Convert PostgreSQL mapping to CrudGenerator mapping
     * @param  string                       $tableName
     * @return MetadataDataObjectPostgreSQL
     */
    private function hydrateDataObject($tableName)
    {
        $dataObject = new MetadataDataObjectPostgreSQL(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $dataObject->setName($tableName);
        $columnDataObject = new MetaDataColumn();

        $statement = $this->pdo->prepare($this->sqlManager->listFieldsQuery());
        $statement->execute(array($tableName));

        $allFields   = $statement->fetchAll(PDO::FETCH_ASSOC);
        $identifiers = $this->getIdentifiers($tableName);

        foreach ($allFields as $metadata) {
            $column = clone $columnDataObject;

            $type = $metadata['type'];

            if (isset($this->typeConversion[$type]) === true) {
                $type = $this->typeConversion[$type];
            }

            $column->setName($metadata['name'])
                   ->setType($type)
                   ->setLength(
                       isset($metadata['character_maximum_length']) === true ?
                       $metadata['character_maximum_length'] :
                       null
                   );
            if (in_array($metadata['name'], $identifiers) === true) {
                $column->setPrimaryKey(true);
            }

            $dataObject->appendColumn($column);
        }

        return $dataObject;
    }

    /**
     * @param string $tableName
     */
    private function getIdentifiers($tableName)
    {
        $identifiers = array();

        $statement = $this->pdo->prepare($this->sqlManager->getAllPrimaryKeys());
        $statement->execute(array($tableName));

        $constraintList = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($constraintList as $containt) {
            $identifiers[] = $containt['column_name'];
        }

        return $identifiers;
    }
}
