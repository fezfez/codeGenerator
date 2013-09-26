<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
namespace CrudGenerator\MetaData\Sources\Doctrine2;

use CrudGenerator\MetaData\Sources\MetaDataDAOInterface;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataRelationColumn;
use CrudGenerator\MetaData\DataObject\MetaDataCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use Doctrine\ORM\EntityManager;

/**
 *
 * Doctrine2 adapter in ZF2 environnement
 */
class Doctrine2MetaDataDAO implements MetaDataDAOInterface
{
    /**
     * @var Doctrine\ORM\EntityManager Entity manager
     */
    private $entityManager = null;

    /**
     * Doctrine2 adapter in ZF2 environnement
     * @param EntityManager $em
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
     * @param string $entityName name of entity
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
     * @param array $metadataCollection array of metadata
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
     * @param \Doctrine\ORM\Mapping\ClassMetadataInfo $metadata Concret metadata
     * @return MetadataDataObjectDoctrine2
     */
    private function hydrateDataObject(\Doctrine\ORM\Mapping\ClassMetadataInfo $metadata, array $parentName = array())
    {
        $dataObject = new MetadataDataObjectDoctrine2(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $columnDataObject = new MetaDataColumn();
        $relationDataObject = new MetaDataRelationColumn();

        foreach ($metadata->fieldMappings as $field => $columnMetadata) {
            $column = clone $columnDataObject;
            $column->setName($field)
                   ->setType($columnMetadata['type'])
                   ->setLength(isset($columnMetadata['length']) ? $columnMetadata['length'] : null)
                   ->setNullable($columnMetadata['nullable']);

            if (in_array($field, $metadata->identifier)) {
                $column->setPrimaryKey(true);
            }

            $dataObject->appendColumn($column);
        }

        foreach ($metadata->getAssociationMappings() as $association) {
            if (in_array($association['targetEntity'], $parentName)) {
                continue;
            }

            $parentName[] = $metadata->name;

            $relation = clone $relationDataObject;
            $relation->setFullName($association['targetEntity'])
                     ->setFieldName($association['fieldName'])
                     ->setMetadata($this->getMetadataFor($association['targetEntity'], $parentName));

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
