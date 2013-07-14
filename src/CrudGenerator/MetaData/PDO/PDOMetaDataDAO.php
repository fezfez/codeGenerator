<?php

namespace CrudGenerator\MetaData\PDO;

use PDO;
use CrudGenerator\MetaData\PDO\PDOConfig;
use CrudGenerator\MetaData\PDO\SqlManager;
use CrudGenerator\MetaData\MetaDataDAOInterface;
use CrudGenerator\MetaData\PDO\MetadataDataObjectPDO;
use CrudGenerator\MetaData\DataObject\MetaDataDataObjectCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObjectCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObject;
use CrudGenerator\MetaData\DataObject\MetaDataRelationDataObjectCollection;

/**
 * PDO adapter
 */
class PDOMetaDataDAO implements MetaDataDAOInterface
{
    /**
     * @var PDO Pdo stmt
     */
    private $pdo       = null;
    /**
     * @var PDOConfig
     */
    private $pdoConfig = null;
    /**
     * @var SqlManager
     */
    private $sqlManager = null;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo, PDOConfig $pdoConfig, SqlManager $sqlManager)
    {
        $this->pdo        = $pdo;
        $this->pdoConfig  = $pdoConfig;
        $this->sqlManager = $sqlManager;
    }

    /* (non-PHPdoc)
     * @see CrudGenerator\MetaData.MetaDataDAOInterface::getAllMetadata()
     */
    public function getAllMetadata()
    {
        $sth = $this->pdo->prepare(
            $this->sqlManager->getAllMetadata(
                $this->pdoConfig->getType()
            )
        );
        $sth->execute();

        $allTables = $sth->fetchAll();

        return $this->doctrine2MetadataToGeneratorMetadata($allTables);
    }

    /**
     * @param array $metadataCollection
     * @return \CrudGenerator\MetaData\DataObject\MetaDataDataObjectCollection
     */
    private function doctrine2MetadataToGeneratorMetadata(array $metadataCollection)
    {
        $metaDataCollection = new MetaDataDataObjectCollection();
        $dataObject = new MetadataDataObjectPDO(
            new MetaDataColumnDataObjectCollection(),
            new MetaDataRelationDataObjectCollection()
        );

        foreach($metadataCollection as $metadata) {
            $realDataObject = clone $dataObject;
            $realDataObject->setName($metadata['table_name']);
            $metaDataCollection->append($realDataObject);
        }

        return $metaDataCollection;
    }

    /**
     * @param \Doctrine\ORM\Mapping\ClassMetadataInfo $metadata
     * @return MetadataDataObjectPDO
     */
    private function hydrateDataObject($tableName)
    {
        $dataObject = new MetadataDataObjectPDO(
            new MetaDataColumnDataObjectCollection(),
            new MetaDataRelationDataObjectCollection()
        );
        $dataObject->setName($tableName);
        $columnDataObject = new MetaDataColumnDataObject();

        $statement = $this->pdo->prepare($this->sqlManager->listFieldsQuery($this->pdoConfig->getType()));
        $statement->execute(array($tableName));
        $allFields = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach($allFields as $field => $metadata) {
            $column = clone $columnDataObject;
            $column->setName($metadata['name'])
                   ->setType($metadata['type'])
                   ->setLength(isset($metadata['character_maximum_length']) ? $metadata['character_maximum_length'] : null);
            $dataObject->appendColumn($column);
        }

        $databaseType = $this->pdoConfig->getType();
        if($databaseType === 'pgsql') {
            $contraintQuery =  'SELECT contype as "type", conkey as "columnNumber"
                FROM pg_class r, pg_constraint c
                WHERE r.oid = c.conrelid
                AND relname = ?;';
            $statement = $this->pdo->prepare($contraintQuery);
            $statement->execute(array($tableName));

            $constraintList = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach($constraintList as $number => $containt) {
                if($containt['type'] === 'p') {
                    $columnNumbers = explode(',', str_replace(array('{', '}'), array('', ''), $containt['columnNumber']));
                    foreach($columnNumbers as $number) {
                        $dataObject->addIdentifier($allFields[($number - 1)]['name']);
                    }
                }
            }
        }

        return $dataObject;
    }

    /**
     * @param string $entity
     * @return MetadataDataObjectPDO
     */
    public function getMetadataFor($tableName)
    {
        return $this->hydrateDataObject($tableName);
    }
}
