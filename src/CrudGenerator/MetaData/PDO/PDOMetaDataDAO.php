<?php

namespace CrudGenerator\MetaData\PDO;

use PDO;
use CrudGenerator\MetaData\MetaDataDAOInterface;
use CrudGenerator\MetaData\Doctrine2\MetadataDataObjectDoctrine2;
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
     * @var PDO
     */
    private $pdo = null;

    private $listFieldsQuery = 'SELECT column_name as name, is_nullable, data_type, character_maximum_length FROM information_schema.columns WHERE table_name = ?;';

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return array
     */
    public function getAllMetadata()
    {
        $sth = $this->pdo->prepare("select table_name from information_schema.tables where table_schema = 'public'");
        $sth->execute();

        $allTables = $sth->fetchAll();

        return $this->doctrine2MetadataToGeneratorMetadata($allTables);
    }

    /**
     * @param array $metadataCollection
     * @return Ambiguous
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
     * @return MetadataDataObjectDoctrine2
     */
    private function hydrateDataObject($tableName)
    {
        $dataObject = new MetadataDataObjectDoctrine2(
            new MetaDataColumnDataObjectCollection(),
            new MetaDataRelationDataObjectCollection()
        );
        $dataObject->setName($tableName);
        $columnDataObject = new MetaDataColumnDataObject();

        $statement = $this->pdo->prepare($this->listFieldsQuery);
        $statement->execute(array($tableName));
        $allFields = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach($allFields as $field => $metadata) {
            $column = clone $columnDataObject;
            $column->setName($metadata['name'])
                   ->setType($metadata['data_type'])
                   ->setLength(isset($metadata['character_maximum_length']) ? $metadata['character_maximum_length'] : null);
            $dataObject->appendColumn($column);
        }

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

        return $dataObject;
    }

    /**
     * @param string $entity
     * @return \Doctrine\ORM\Mapping\ClassMetadataInfo
     */
    public function getMetadataFor($tableName)
    {
        return $this->hydrateDataObject($tableName);
    }
}
