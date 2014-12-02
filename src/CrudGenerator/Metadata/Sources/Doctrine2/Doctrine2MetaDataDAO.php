<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Sources\Doctrine2;

use CrudGenerator\Metadata\Sources\MetaDataDAOInterface;
use CrudGenerator\Metadata\DataObject\MetaDataRelationColumn;
use CrudGenerator\Metadata\DataObject\MetaDataCollection;
use CrudGenerator\Metadata\DataObject\MetaDataColumnCollection;
use CrudGenerator\Metadata\DataObject\MetaDataColumn;
use CrudGenerator\Metadata\DataObject\MetaDataRelationCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;

/**
 *
 * Doctrine2 adapter in ZF2 environnement
 */
class Doctrine2MetaDataDAO implements MetaDataDAOInterface
{
    /**
     * @var EntityManager Entity manager
     */
    private $entityManager = null;
    /**
     * @var array
     */
    private $relationConverter = array(
        ClassMetadataInfo::ONE_TO_MANY  => MetaDataRelationColumn::ONE_TO_MANY,
        ClassMetadataInfo::ONE_TO_ONE   => MetaDataRelationColumn::ONE_TO_ONE,
        ClassMetadataInfo::MANY_TO_MANY => MetaDataRelationColumn::MANY_TO_MANY,
        ClassMetadataInfo::MANY_TO_ONE  => MetaDataRelationColumn::MANY_TO_ONE,
    );

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Getting all medata from aviable entities
     *
     * @return MetaDataCollection
     */
    public function getAllMetadata()
    {
        return $this->doctrine2MetadataToGeneratorMetadata(
            $this->entityManager->getMetaDataFactory()->getAllMetadata()
        );
    }

    /**
     * Getting metadata for a particulary entity
     *
     * @param  string                      $entityName name of entity
     * @return MetadataDataObjectDoctrine2
     */
    public function getMetadataFor($entityName, array $parentName = array())
    {
        return $this->hydrateDataObject(
            $this->entityManager->getMetadataFactory()->getMetadataFor($entityName),
            $parentName
        );
    }

    /**
     * Transform a doctrine2 metadata into generator metadata
     *
     * @param  array              $metadataCollection array of metadata
     * @return MetaDataCollection
     */
    private function doctrine2MetadataToGeneratorMetadata(array $metadataCollection)
    {
        $metaDataCollection = new MetaDataCollection();

        foreach ($metadataCollection as $metadata) {
            $metaDataCollection->append(
                $this->hydrateDataObject($metadata)
            );
        }

        return $metaDataCollection;
    }

    /**
     * Transform a doctrine2 metadata into generator metadata
     *
     * @param  ClassMetadataInfo           $metadata Concret metadata
     * @return MetadataDataObjectDoctrine2
     */
    private function hydrateDataObject(ClassMetadata $metadata, array $parentName = array())
    {
        $dataObject = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $columnDataObject = new MetaDataColumn();

        foreach ($metadata->fieldMappings as $field => $columnMetadata) {
            $column = clone $columnDataObject;
            $column->setName($field)
                   ->setType($columnMetadata['type'])
                   ->setLength(isset($columnMetadata['length']) === true ? $columnMetadata['length'] : null)
                   ->setNullable($columnMetadata['nullable'])
                   ->setPrimaryKey((in_array($field, $metadata->identifier) === true ? true : false));

            $dataObject->appendColumn($column);
        }

        $dataObject = $this->hydrateAssociation($metadata, $dataObject, $parentName);

        $dataObject->setName($metadata->name);

        return $dataObject;
    }

    /**
     * @param  ClassMetadataInfo           $metadata
     * @param  MetadataDataObjectDoctrine2 $dataObject
     * @param  array                       $parentName
     * @return MetadataDataObjectDoctrine2
     */
    private function hydrateAssociation(
        ClassMetadata $metadata,
        MetadataDataObjectDoctrine2 $dataObject,
        array $parentName = array()
    ) {
        $relationDataObject = new MetaDataRelationColumn();

        foreach ($metadata->getAssociationMappings() as $association) {
            if (in_array($association['targetEntity'], $parentName) === true) {
                continue;
            }

            $parentName[] = $metadata->name;

            $relation = clone $relationDataObject;
            $relation->setFullName($association['targetEntity'])
                     ->setFieldName($association['fieldName'])
                     ->setMetadata($this->getMetadataFor($association['targetEntity'], $parentName));

            if (isset($this->relationConverter[$association['type']]) === true) {
                $relation->setAssociationType($this->relationConverter[$association['type']]);
            }

            $dataObject->appendRelation($relation);
        }

        return $dataObject;
    }
}
