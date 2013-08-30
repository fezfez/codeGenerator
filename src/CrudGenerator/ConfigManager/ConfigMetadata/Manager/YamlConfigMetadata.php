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
use CrudGenerator\ConfigManager\ConfigMetadata\DataObject\Property;
use CrudGenerator\ConfigManager\ConfigMetadata\DataObject\PropertiesCollection;

class YamlConfigMetadata
{
    /**
     * @var array yamlDatas
     */
    private $yamlDatas  = array();

    public function __construct($yamlDatas)
    {
        $this->yamlDatas = $yamlDatas;
    }

    public function getMetadatas()
    {
        $yamlConfigMetadataDTO = new YamlConfigDataObject();
        $propertiesCollection = new PropertiesCollection();
        $property = new Property();

        foreach ($this->yamlDatas['package'] as $packageName => $packageDatas) {
            $yamlConfigMetadataDTO->setPackageName($packageName);
            if (isset($packageDatas['PackageFolder'])) {
                $yamlConfigMetadataDTO->setPackageEnabled($packageDatas['PackageFolder']);
            }

            foreach ($packageDatas['module'] as $moduleName => $moduleDatas) {
                $yamlConfigMetadataDTO->setName($moduleName);
                if (isset($moduleDatas['options'])) {
                    $yamlConfigMetadataDTO->setOptions($moduleDatas['options']);
                }

                foreach ($moduleDatas['properties'] as $propertyName => $propertyDatas) {
                    $propertyDTO = clone $property;

                    $propertyDTO->setName($propertyName);

                    if (isset($propertyDatas['type'])) {
                        $propertyDTO->setType($propertyDatas['type']);
                    }
                    if (isset($propertyDatas['options'])) {
                        $propertyDTO->setOptions($propertyDatas['options']);
                    }
                    $propertiesCollection->append($propertyDTO);
                }
            }
        }
        $yamlConfigMetadataDTO->setPropertiesCollection($propertiesCollection);
        $yamlConfigMetadataDTO->setGenerators($packageDatas['Generators']);

        return $yamlConfigMetadataDTO;
    }
}
