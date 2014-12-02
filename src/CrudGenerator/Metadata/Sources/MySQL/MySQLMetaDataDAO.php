<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Sources\MySQL;

use PDO;
use CrudGenerator\Metadata\Sources\MetaDataDAOInterface;
use CrudGenerator\Metadata\DataObject\MetaDataCollection;
use CrudGenerator\Metadata\DataObject\MetaDataColumnCollection;
use CrudGenerator\Metadata\DataObject\MetaDataColumn;
use CrudGenerator\Metadata\DataObject\MetaDataRelationCollection;
use CrudGenerator\Metadata\DataObject\MetaDataRelationColumn;
use CrudGenerator\Metadata\Driver\DriverConfig;

/**
 * MySQL adapter
 */
class MySQLMetaDataDAO implements MetaDataDAOInterface
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
     * MySQL adapter
     * @param PDO          $pdo
     * @param DriverConfig $pdoConfig
     */
    public function __construct(PDO $pdo, DriverConfig $pdoConfig)
    {
        $this->pdo       = $pdo;
        $this->pdoConfig = $pdoConfig;
    }

    /**
     * Get all metadata from MySQL
     *
     * @return MetaDataCollection
     */
    public function getAllMetadata()
    {
        $sth = $this->pdo->prepare('show tables');

        $sth->execute();

        return $this->pdoMetadataToGeneratorMetadata(
            $sth->fetchAll(
                PDO::FETCH_COLUMN,
                0
            )
        );
    }

    /**
     * Get particularie metadata from MySQL
     *
     * @param  string                                                        $tableName
     * @return \CrudGenerator\Metadata\Sources\MySQL\MetadataDataObjectMySQL
     */
    public function getMetadataFor($tableName, array $parentName = array())
    {
        return $this->hydrateDataObject($tableName, $parentName);
    }

    /**
     * Convert MySQL mapping to CrudGenerator mapping
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
     * Convert MySQL mapping to CodeGenerator mapping
     * @param  string                  $tableName
     * @return MetadataDataObjectMySQL
     */
    private function hydrateDataObject($tableName, array $parentName = array())
    {
        $dataObject = new MetadataDataObjectMySQL(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $dataObject->setName($tableName);

        $statement = $this->pdo->prepare(
            "SELECT
                columnTable.column_name, columnTable.table_name,
                columnTable.data_type, columnTable.column_key, columnTable.is_nullable,
                k.referenced_table_name, k.referenced_column_name
            FROM information_schema.columns as columnTable
            left outer join information_schema.key_column_usage k
              on k.table_schema=columnTable.table_schema
              and k.table_name=columnTable.table_name
              and k.column_name=columnTable.column_name
            WHERE columnTable.table_name = :tableName
              AND columnTable.table_schema = :databaseName"
        );

        $statement->execute(
            array(
                ':tableName'    => $tableName,
                ':databaseName' => $this->pdoConfig->getResponse('configDatabaseName'),
            )
        );

        $allFields = $statement->fetchAll(PDO::FETCH_ASSOC);

        $columnsAssociation = array();

        foreach ($allFields as $index => $metadata) {
            if ($metadata['referenced_table_name'] !== null && $metadata['referenced_column_name'] !== null) {
                $columnsAssociation[$index] = $metadata;
                unset($allFields[$index]);
                continue;
            }
        }

        return $this->hydrateRelation(
            $this->hydrateFields($dataObject, $allFields),
            $columnsAssociation,
            $parentName
        );
    }

    /**
     * @param  MetadataDataObjectMySQL $dataObject
     * @param  array                   $allFields
     * @return MetadataDataObjectMySQL
     */
    private function hydrateFields(MetadataDataObjectMySQL $dataObject, array $allFields)
    {
        $columnDataObject = new MetaDataColumn();

        foreach ($allFields as $metadata) {
            $column = clone $columnDataObject;

            $type = $metadata['data_type'];

            if (isset($this->typeConversion['data_type']) === true) {
                $type = $this->typeConversion['data_type'];
            }

            $column->setName($metadata['column_name'])
                   ->setType($type)
                   ->setLength(null)
                   ->setPrimaryKey(($metadata['column_key'] === 'PRI') ? true : false);

            $dataObject->appendColumn($column);
        }

        return $dataObject;
    }

    /**
     * @param  MetadataDataObjectMySQL $dataObject
     * @param  array                   $columnsAssociation
     * @param  array                   $parentName
     * @return MetadataDataObjectMySQL
     */
    private function hydrateRelation(MetadataDataObjectMySQL $dataObject, array $columnsAssociation, array $parentName)
    {
        $relationDataObject = new MetaDataRelationColumn();

        foreach ($columnsAssociation as $association) {
            if (in_array($association['referenced_table_name'], $parentName) === true) {
                continue;
            }

            $parentName[] = $dataObject->getName();

            $relation = clone $relationDataObject;
            $relation->setFullName($association['referenced_table_name'])
                     ->setFieldName($association['referenced_column_name'])
                     ->setAssociationType(MetaDataRelationColumn::UNKNOWN)
                     ->setMetadata($this->getMetadataFor($association['referenced_table_name'], $parentName));

            $dataObject->appendRelation($relation);
        }

        return $dataObject;
    }
}
