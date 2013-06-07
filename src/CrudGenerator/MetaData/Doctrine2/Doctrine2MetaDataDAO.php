<?php

namespace CrudGenerator\MetaData\Doctrine2;

use CrudGenerator\MetaData\DataObject\MetaDataRelationColumnDataObject;

use Doctrine\ORM\EntityManager;
use CrudGenerator\MetaData\MetaDataDAOInterface;
use CrudGenerator\MetaData\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataDataObjectCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObjectCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObject;
use CrudGenerator\MetaData\DataObject\MetaDataRelationDataObjectCollection;

/**
 *
 * Doctrine2 adapter in ZF2 environnement
 */
class Doctrine2MetaDataDAO implements MetaDataDAOInterface
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em = null;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getAllMetadata()
    {
        return $this->doctrine2MetadataToGeneratorMetadata($this->em->getMetaDataFactory()->getAllMetadata());
    }

    /**
     * @param array $metadataCollection
     * @return MetaDataDataObjectCollection
     */
    private function doctrine2MetadataToGeneratorMetadata(array $metadataCollection)
    {
        $metaDataCollection = new MetaDataDataObjectCollection();
        $dataObject = new MetadataDataObjectDoctrine2(
            new MetaDataColumnDataObjectCollection(),
            new MetaDataRelationDataObjectCollection()
        );
        foreach($metadataCollection as $metadata) {
            $realDataObject = clone $dataObject;
            $realDataObject->setName($metadata->getName());
            $metaDataCollection->append($metadata);
        }

        return $metaDataCollection;
    }

    /**
     * @param \Doctrine\ORM\Mapping\ClassMetadataInfo $metadata
     * @return MetadataDataObjectDoctrine2
     */
    private function hydrateDataObject(\Doctrine\ORM\Mapping\ClassMetadataInfo $metadata)
    {
        $dataObject = new MetadataDataObjectDoctrine2(
            new MetaDataColumnDataObjectCollection(),
            new MetaDataRelationDataObjectCollection()
        );
        $columnDataObject = new MetaDataColumnDataObject();
        $relationDataObject = new MetaDataRelationColumnDataObject();

        foreach($metadata->identifier as $identifier) {
            $dataObject->addIdentifier($identifier);
        }

        foreach($metadata->fieldMappings as $field => $columnMetadata) {
            $column = clone $columnDataObject;
            $column->setName($field)
                   ->setType($columnMetadata['type'])
                   ->setLength(isset($columnMetadata['length']) ? $columnMetadata['length'] : null)
                   ->setNullable($columnMetadata['nullable']);
            $dataObject->appendColumn($column);
        }

        foreach($metadata->getAssociationMappings() as $association) {
            $relation = clone $relationDataObject;
            $relation->setFullName($association['targetEntity']);
            $dataObject->appendRelation($relation);
        }

        return $dataObject;
    }

    /**
     * @param string $entity
     * @return MetadataDataObjectDoctrine2
     */
    public function getMetadataFor($entity)
    {
        return $this->hydrateDataObject($this->em->getMetadataFactory()->getMetadataFor($entity));
    }
}
