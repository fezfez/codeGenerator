<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Sources\Json;

use CrudGenerator\Metadata\DataObject\MetaDataCollection;
use CrudGenerator\Metadata\DataObject\MetaDataColumn;
use CrudGenerator\Metadata\DataObject\MetaDataColumnCollection;
use CrudGenerator\Metadata\DataObject\MetaDataRelationCollection;
use CrudGenerator\Metadata\DataObject\MetaDataRelationColumn;
use CrudGenerator\Metadata\Driver\DriverConfig;
use CrudGenerator\Metadata\Driver\File\FileDriverInterface;
use CrudGenerator\Metadata\Sources\MetaDataDAOInterface;
use CrudGenerator\Utils\Installer;
use JSONSchema\SchemaGenerator;

/**
 * Json adapter
 */
class JsonMetaDataDAO implements MetaDataDAOInterface
{
    /**
     * @var FileDriverInterface
     */
    private $jsonConfig = null;
    /**
     * @var DriverConfig
     */
    private $config = null;
    /**
     * @var SchemaGenerator
     */
    private $schemaGenerator = null;

    /**
     * Constructor.
     * @param FileDriverInterface $jsonConfig
     * @param DriverConfig        $config
     * @param SchemaGenerator     $schemaGenerator
     */
    public function __construct(FileDriverInterface $jsonConfig, DriverConfig $config, SchemaGenerator $schemaGenerator)
    {
        $this->jsonConfig      = $jsonConfig;
        $this->config          = $config;
        $this->schemaGenerator = $schemaGenerator;
    }

    /**
     * Get all metadata from MySQL
     *
     * @return MetaDataCollection
     */
    public function getAllMetadata()
    {
        $schema   = $this->schemaGenerator->parse($this->jsonConfig->getFile($this->config));
        $filePath = Installer::getDirectory(Installer::TMP) . 'schema.json';

        file_put_contents($filePath, $schema->toString());

        $collection = new MetaDataCollection();

        if ($this->isFirstLevelIsDto($schema) === true) {
            $metadata = $this->createMetadata('master');
            $metadata = $this->hydrateProperties($schema->getProperties(), $metadata, $collection);
            $collection->append($metadata);
        } else {
            // each proporties is considered as metadata
            foreach ($schema->getProperties() as $propName => $prop) {
                if (count($prop->getProperties()) === 0) { // No column, the relation replace column
                    foreach ($prop->getItems() as $itemValue) {
                        /* @var $itemValue \JSONSchema\Structure\Item */
                        $metadata = $this->createMetadata($propName);
                        $metadata = $this->hydrateProperties($itemValue->getProperties(), $metadata, $collection);
                        $collection->append($metadata);
                    }
                } else { // have column, hydrate them
                    $metadata = $this->createMetadata($propName);
                    $metadata = $this->hydrateProperties($prop->getProperties(), $metadata, $collection);
                    $metadata = $this->hydrateItems($propName, $prop->getItems(), $metadata, $collection);
                    $collection->append($metadata);
                }
            }
        }

        return $collection;
    }

    /**
     * Is there is only array or object at the first level,
     * its not considered as a metadata but as a collection of it
     *
     * @param  \JSONSchema\Structure\Schema $schema
     * @return boolean
     */
    private function isFirstLevelIsDto(\JSONSchema\Structure\Schema $schema)
    {
        $isFirstLevelIsDto = false;

        foreach ($schema->getProperties() as $propertie) {
            if (in_array($propertie->getType(), array('object', 'array')) === false) {
                $isFirstLevelIsDto = true;
            }
        }

        return $isFirstLevelIsDto;
    }

    /**
     * @param  string                 $name
     * @throws \Exception
     * @return MetadataDataObjectJson
     */
    private function createMetadata($name)
    {
        if (is_string($name) === false) {
            throw new \Exception(sprintf('Metadata name must be a string "%s" given', gettype($name)));
        }

        $dto = new MetadataDataObjectJson(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $dto->setName($name);

        return $dto;
    }

    /**
     * @param  string                                                    $name
     * @param  array                                                     $props
     * @param  MetadataDataObjectJson                                    $metadata
     * @return \CrudGenerator\Metadata\DataObject\MetaDataRelationColumn
     */
    private function hydrateMetaDataRelationColumn(
        $name,
        array $props,
        MetadataDataObjectJson $metadata,
        MetaDataCollection $mainCollection
    ) {
        $relation = new MetaDataRelationColumn();
        $relation->setFieldName($name);
        $relation->setFullName($name);

        $metadataRelation = $this->createMetadata($name);
        $metadataRelation = $this->hydrateProperties($props, $metadataRelation, $mainCollection);
        $relation->setMetadata($metadataRelation);

        $mainCollection->append($metadataRelation); // As this metatdata to the main metadataCollection

        return $relation;
    }

    /**
     * @param  array    $items
     * @param  string[] $types
     * @return boolean
     */
    private function itemsAreAllOfType(array $items, array $types)
    {
        $allOfType = true;

        foreach ($items as $propName => $item) {
            if (in_array($item->getType(), $types) === false) {
                $allOfType = false;
                break;
            }
        }

        return $allOfType;
    }

    /**
     * Items is considered as relation
     *
     * @param  string                 $daddyName
     * @param  array                  $items
     * @param  MetadataDataObjectJson $metadata
     * @return MetadataDataObjectJson
     */
    private function hydrateItems(
        $daddyName,
        array $items,
        MetadataDataObjectJson $metadata,
        MetaDataCollection $mainCollection
    ) {
        $mergeArray = true;

        if ($mergeArray === true && $this->itemsAreAllOfType($items, array('object')) === true) {
            $mergedArray = array();

            foreach ($items as $item) {
                foreach ($item->getProperties() as $itemName => $itemValue) {
                    $mergedArray[$itemName] = $itemValue;
                }
            }

            $metadata->appendRelation(
                $this->hydrateMetaDataRelationColumn($daddyName, $mergedArray, $metadata, $mainCollection)
            );
        } else {
            $specialProperties = array();

            foreach ($items as $propName => $item) {
                if (in_array($item->getType(), array('object', 'array')) === false) {
                    $specialProperties[$propName] = $item;
                    continue;
                }

                $metadata->appendRelation(
                    $this->hydrateMetaDataRelationColumn($daddyName, $item->getProperties(), $metadata, $mainCollection)
                );
            }
        }

        return $metadata;
    }

    /**
     * Properties is considered as column
     *
     * @param  array                  $properties
     * @param  MetadataDataObjectJson $metadata
     * @param  MetaDataCollection     $mainCollection
     * @throws \Exception
     * @return MetadataDataObjectJson
     */
    private function hydrateProperties(
        array $properties,
        MetadataDataObjectJson $metadata,
        MetaDataCollection $mainCollection
    ) {
        $specialProperties = array();

        foreach ($properties as $propName => $propertie) {
            if (false === ($propertie instanceof \JSONSchema\Structure\Property)) {
                throw new \Exception('$propertie is not an instance of Property');
            }
            if (in_array($propertie->getType(), array('object', 'array')) === true) {
                $specialProperties[$propName] = $propertie;
                continue;
            }

            $column = new MetaDataColumn();
            $column->setNullable(!$propertie->getRequired());
            $column->setName($propertie->getName());
            $column->setType($propertie->getType());

            $metadata->appendColumn($column);
        }

        if ($specialProperties !== array()) {
            foreach ($specialProperties as $prop) {
                $metadata->appendRelation(
                    $this->hydrateMetaDataRelationColumn(
                        $prop->getName(),
                        $prop->getProperties(),
                        $metadata,
                        $mainCollection
                    )
                );
            }
        }

        return $metadata;
    }

    /**
     * Get particularie metadata from jsin
     *
     * @param  string                                                      $tableName
     * @return \CrudGenerator\Metadata\Sources\Json\MetadataDataObjectJson
     */
    public function getMetadataFor($tableName, array $parentName = array())
    {
        $avaibleData = array();
        foreach ($this->getAllMetadata() as $metadata) {
            $avaibleData[] = $metadata->getName();
            if ($metadata->getName() === $tableName) {
                return $metadata;
            }
        }

        throw new \Exception(sprintf('"%s" not found in "%s"', $tableName, implode(', ', $avaibleData)));
    }
}
