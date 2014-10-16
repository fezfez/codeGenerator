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
namespace CrudGenerator\MetaData\Sources\Json;

use CrudGenerator\MetaData\Sources\Json\MetadataDataObjectJson;
use CrudGenerator\MetaData\Sources\MetaDataDAOInterface;
use CrudGenerator\MetaData\DataObject\MetaDataCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationColumn;
use JSONSchema\SchemaGenerator;
use CrudGenerator\MetaData\Driver\File\FileDriverInterface;

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
     * @var SchemaGenerator
     */
    private $schemaGenerator = null;

    /**
     * Constructor.
     * @param FileDriverInterface $jsonConfig
     * @param SchemaGenerator $schemaGenerator
     * @param \CrudGenerator\MetaData\Driver\DriverConfig $config
     */
    public function __construct(FileDriverInterface $jsonConfig, $config, SchemaGenerator $schemaGenerator)
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
        $schema = $this->schemaGenerator->parse($this->jsonConfig->getFile($this->config));

        $collection = new MetaDataCollection();

        if ($this->isFirstLevelIsDto($schema) === true) {
            $metadata = $this->createMetadata('master');
            $metadata = $this->hydrateProperties($schema->getProperties(), $metadata);
            $collection->append($metadata);
        } else {
            // each proporties is considered as metadata
            foreach ($schema->getProperties() as $propName => $prop) {
                $metadata = $this->createMetadata($propName);
                $metadata = $this->hydrateProperties($prop->getProperties(), $metadata);
                $metadata = $this->hydrateItems($propName, $prop->getItems(), $metadata);
                $collection->append($metadata);
            }
        }

        return $collection;
    }

    /**
     * Is there is only array or object at the first level,
     * its not considered as a metadata but as a collection of it
     *
     * @param \JSONSchema\Structure\Schema $schema
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

    private function createMetadata($name)
    {
        if (is_string($name) === false) {
            throw new \Exception('Name must be a str "' . gettype($name) . '"');
        }

        $dto = new MetadataDataObjectJson(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $dto->setName($name);

        return $dto;
    }

    private function hydrateMetaDataRelationColumn($name, array $props, MetadataDataObjectJson $metadata)
    {
        $relation = new MetaDataRelationColumn();
        $relation->setFieldName($name);
        $relation->setFullName($name);

        $metadataRelation = $this->createMetadata($name);
        $metadataRelation = $this->hydrateProperties($props, $metadata);
        $relation->setMetadata($metadataRelation);

        return $relation;
    }

    private function hydrateItems($daddyName, array $items, MetadataDataObjectJson $metadata)
    {
        $specialProperties = array();
        $mergeArray        = true;
        $allObject         = true;
        $mergedArray       = array();

        foreach ($items as $propName => $item) {
            if (in_array($item->getType(), array('object')) === false) {
                $allObject = false;
                break;
            }
        }

        if ($mergeArray === true && $allObject === true) {
            foreach ($items as $item) {
                foreach ($item->getProperties() as $itemName => $itemValue) {
                    $mergedArray[$itemName] = $itemValue;
                }
            }

            $metadata->appendRelation(
                $this->hydrateMetaDataRelationColumn($daddyName, $mergedArray, $metadata)
            );

        } else {
            foreach ($items as $propName => $item) {
                if (in_array($item->getType(), array('object', 'array')) === false) {
                    $specialProperties[$propName] = $item;
                    continue;
                }

                $metadata->appendRelation(
                    $this->hydrateMetaDataRelationColumn($daddyName, $item->getProperties(), $metadata)
                );
            }
        }

        return $metadata;
    }

    private function hydrateProperties(array $properties, MetadataDataObjectJson $metadata)
    {
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
                    $this->hydrateMetaDataRelationColumn($prop->getName(), $prop->getProperties(), $metadata)
                );
            }
        }

        return $metadata;
    }

    /**
     * Get particularie metadata from MySQL
     *
     * @param string $tableName
     * @return \CrudGenerator\MetaData\Sources\MySQL\MetadataDataObjectMySQL
     */
    public function getMetadataFor($tableName, array $parentName = array())
    {
        foreach ($this->getAllMetadata() as $metadata) {
            if ($metadata->getName() === $tableName) {
                return $metadata;
            }
        }

        throw new \Exception(sprintf('"%s" not found', $tableName));
    }
}
