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
namespace CrudGenerator\ConfigManager\ConfigMetadata\Manager;

/**
 * ConfigGenerator manager instance
 *
 * @author Anthony Rochet
 */
use CrudGenerator\ConfigManager\ConfigMetadata\DataObject\YamlConfigDataObject;
use CrudGenerator\ConfigManager\ConfigMetadata\DataObject\MetadataDataObjectConfig;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\DataObject;

class YamlConfigMetadata
{
    /**
     * @var array yamlDatas
     */
    private $yamlDatas  = array();

    /**
     * @param array $yamlDatas
     */
    public function __construct($yamlDatas)
    {
        $this->yamlDatas = $yamlDatas;
    }

    /**
     * @return \CrudGenerator\ConfigManager\ConfigMetadata\DataObject\YamlConfigDataObject
     */
    public function getMetadatas()
    {
        $yamlConfigMetadataDTO = new YamlConfigDataObject();
        $metaData              = new MetadataDataObjectConfig(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $metaDataColumn        = new MetaDataColumn();

        foreach ($this->yamlDatas['package'] as $packageName => $packageDatas) {
            $yamlConfigMetadataDTO->setPackageName($packageName);
            if (isset($packageDatas['PackageFolder'])) {
                $yamlConfigMetadataDTO->setPackageEnabled($packageDatas['PackageFolder']);
            }

            foreach ($packageDatas['module'] as $moduleName => $moduleDatas) {
                $metaData->setName($moduleName);

                foreach ($moduleDatas['properties'] as $propertyName => $propertyDatas) {
                    $column = clone $metaDataColumn;

                    $column->setName($propertyName);

                    if (isset($propertyDatas['type'])) {
                        $column->setType($propertyDatas['type']);
                    }

                    if (isset($propertyDatas['options'])) {
                        $options = $propertyDatas['options'];
                        if (isset($options['pk']) && $options['pk'] === true) {
                            $column->setPrimaryKey(true);
                        }
                    }
                    $metaData->appendColumn($column);
                }
            }
        }

        $yamlConfigMetadataDTO->setMetadata($metaData);
        $yamlConfigMetadataDTO->setGenerators(array_keys($packageDatas['Generators']));

        foreach ($packageDatas['Generators'] as $generatorName => $options) {
            if (isset($options['options']) && is_array($options['options'])) {
                foreach ($options['options'] as $optionName => $optionValue) {
                    $yamlConfigMetadataDTO->addGeneratorsOptions($generatorName, $optionName, $optionValue);
                }
            }
        }

        return $yamlConfigMetadataDTO;
    }

    /**
     * @param string $generatorName
     * @param DataObject $dataObject
     * @return DataObject
     */
    public function writeAbstractOptions($generatorName, DataObject $dataObject)
    {
        $dataObject = clone $dataObject;

        foreach ($this->yamlDatas['package'] as $packageName => $packageDatas) {
            if (isset($packageDatas['Generators'][$generatorName]['options'])) {
                foreach ($packageDatas['Generators'][$generatorName]['options'] as $optionName => $optionValue) {
                    $methodName = 'set' . ucfirst($optionName);
                    if (method_exists($dataObject, $methodName)) {
                        $dataObject->$methodName($optionValue);
                    }
                }
            }
        }

        return $dataObject;
    }
}
