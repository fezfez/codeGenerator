<?php

namespace CrudGenerator\MetaData\Doctrine2;

use CrudGenerator\MetaData\DataObject\MetaDataRelationColumnDataObject;
use CrudGenerator\MetaData\MetaDataDAOInterface;
use CrudGenerator\MetaData\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataDataObjectCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObjectCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObject;
use CrudGenerator\MetaData\DataObject\MetaDataRelationDataObjectCollection;
use Doctrine\ORM\EntityManager;

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
     * Getting all medata from aviable entities
     *
     * @return MetaDataDataObjectCollection
     */
    public function getAllMetadata()
    {
        return $this->doctrine2MetadataToGeneratorMetadata(
            $this->em->getMetaDataFactory()->getAllMetadata()
        );
    }

    /**
     * Getting metadata for a particulary entity
     *
     * @param string $entityName name of entity
     * @return MetadataDataObjectDoctrine2
     */
    public function getMetadataFor($entityName)
    {
        return $this->hydrateDataObject(
            $this->em->getMetadataFactory()->getMetadataFor($entityName)
        );
    }

    /**
     * Transform a doctrine2 metadata into generator metadata
     *
     * @param array $metadataCollection array of metadata
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
     * Transform a doctrine2 metadata into generator metadata
     *
     * @param \Doctrine\ORM\Mapping\ClassMetadataInfo $metadata Concret metadata
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
            $relation->setFullName($association['targetEntity'])
                     ->setFieldName($association['fieldName']);

            if (\Doctrine\ORM\Mapping\ClassMetadataInfo::ONE_TO_MANY === $association['type']) {
                $relation->setAssociationType('oneToMany');
            } elseif (\Doctrine\ORM\Mapping\ClassMetadataInfo::ONE_TO_ONE === $association['type']) {
                $relation->setAssociationType('oneToOne');
            } elseif (\Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY === $association['type']) {
                $relation->setAssociationType('manyToMany');
            } elseif (\Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_ONE === $association['type']) {
                $relation->setAssociationType('manyToOne');
            }

            $dataObject->appendRelation($relation);
        }

        $dataObject->setName($metadata->name);

        return $dataObject;
    }
}
