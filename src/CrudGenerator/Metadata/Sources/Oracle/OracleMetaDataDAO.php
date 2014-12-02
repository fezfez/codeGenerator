<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Sources\Oracle;

use PDO;
use CrudGenerator\Metadata\Sources\MetaDataDAOInterface;
use CrudGenerator\Metadata\DataObject\MetaDataCollection;
use CrudGenerator\Metadata\DataObject\MetaDataColumnCollection;
use CrudGenerator\Metadata\DataObject\MetaDataColumn;
use CrudGenerator\Metadata\DataObject\MetaDataRelationCollection;

/**
 * Oracle adapter
 */
class OracleMetaDataDAO implements MetaDataDAOInterface
{
    /**
     * @var PDO Pdo stmt
     */
    private $pdo = null;

    /**
     * PDO adapter
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get all metadata from PDO
     *
     * @return MetaDataCollection
     */
    public function getAllMetadata()
    {
        $sth = $this->pdo->prepare(
            "select OWNER , TABLE_NAME as TABLE_NAME
            from SYS.ALL_TABLES
            where TABLESPACE_NAME = 'TBLSP_DATA' and OWNER not like 'SYS%'
            order by OWNER, TABLE_NAME"
        );

        $sth->execute();

        return $this->pdoMetadataToGeneratorMetadata(
            $sth->fetchAll(
                PDO::FETCH_ASSOC
            )
        );
    }

    /**
     * Get particularie metadata from PDO
     *
     * @param  string                   $tableName
     * @return MetadataDataObjectOracle
     */
    public function getMetadataFor($tableName, array $parentName = array())
    {
        return $this->hydrateDataObject($tableName);
    }

    /**
     * Convert PDOmapping to CrudGenerator mapping
     * @param  array                                                 $metadataCollection
     * @return \CrudGenerator\Metadata\DataObject\MetaDataCollection
     */
    private function pdoMetadataToGeneratorMetadata(array $metadataCollection)
    {
        $metaDataCollection = new MetaDataCollection();

        foreach ($metadataCollection as $data) {
            $metaDataCollection->append(
                $this->hydrateDataObject($data)
            );
        }

        return $metaDataCollection;
    }

    /**
     * Convert PDOmapping to CrudGenerator mapping
     * @return MetadataDataObjectOracle
     */
    private function hydrateDataObject($data)
    {
        $dataObject = new MetadataDataObjectOracle(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $tableName = $data['TABLE_NAME'];
        $owner     = $data['OWNER'];

        $dataObject->setName($owner.'/'.$tableName);
        $columnDataObject = new MetaDataColumn();

        $statement = $this->pdo->prepare(
            sprintf(
                "SELECT COLUMN_NAME as name, NULLABLE as  nullable, DATA_TYPE as type, DATA_LENGTH as length
                FROM SYS.ALL_TAB_COLUMNS
                WHERE TABLE_NAME = '%s' and OWNER= '%s'",
                $tableName,
                $owner
            )
        );
        $statement->execute(array());

        $allFields   = $statement->fetchAll(PDO::FETCH_ASSOC);
        $identifiers = $this->getIdentifiers($owner, $tableName);

        foreach ($allFields as $metadata) {
            $column = clone $columnDataObject;

            $type = $metadata['type'];

            if (isset($this->typeConversion[$type]) === true) {
                $type = $this->typeConversion[$type];
            }

            $column->setName($metadata['name'])
                   ->setType($type)
                   ->setLength(
                       isset($metadata['length']) === true ?
                       $metadata['length'] :
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
     * @param  string $tableName
     * @return array
     */
    private function getIdentifiers($owner, $tableName)
    {
        $identifiers = array();

        $statement = $this->pdo->prepare(
            printf("select cc.COLUMN_NAME
             from USER_CONSTRAINTS c
             join ALL_CONS_COLUMNS cc
            on c.CONSTRAINT_NAME = cc.CONSTRAINT_NAME
            and  c.OWNER= cc.OWNER
             where c.table_name like '%s'
             and c.owner like '%s'
             and CONSTRAINT_TYPE = '%s'", $owner, $tableName, "P")
        );
        $statement->execute(array($tableName));

        $constraintList = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($constraintList as $containt) {
            $identifiers[] = $containt['column_name'];
        }

        return $identifiers;
    }
}
