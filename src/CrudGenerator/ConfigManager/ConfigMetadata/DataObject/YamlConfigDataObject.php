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
namespace CrudGenerator\ConfigManager\ConfigMetadata\DataObject;

use CrudGenerator\ConfigManager\ConfigMetadata\DataObject\MetadataDataObjectConfig;

/**
 * Yaml datas for conversion into generic metadatas
 *
 * @author Anthony Rochet
 */
class YamlConfigDataObject
{
    /**
     * @var string packageName
     */
    private $packageName          = null;
    /**
     * @var boolean packageEnabled
     */
    private $packageEnabled       = null;
    /**
     * @var string name
     */
    private $name                 = null;
    /**
     * @var array options
     */
    private $options              = null;
    /**
     * @var array generators
     */
    private $generators           = null;
    /**
     * @var array generators options
     */
    private $generatorsOptions    = null;
    /**
     * @var MetadataDataObjectConfig
     */
    private $metadata             = null;
    /*
     * SETTERS
     */

    /**
     * @param string $value
     * @return \CrudGenerator\ConfigManager\ConfigMetadata\DataObject\YamlConfigDataObject
     */
    public function setPackageName($value)
    {
        $this->packageName = $value;
        return $this;
    }
    /**
     * @param boolean $value
     * @return \CrudGenerator\ConfigManager\ConfigMetadata\DataObject\YamlConfigDataObject
     */
    public function setPackageEnabled($value)
    {
        $this->packageEnabled = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\ConfigManager\ConfigMetadata\DataObject\YamlConfigDataObject
     */
    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }
    /**
     * @param array $value
     * @return \CrudGenerator\ConfigManager\ConfigMetadata\DataObject\YamlConfigDataObject
     */
    public function setOptions($value)
    {
        $this->options = $value;
        return $this;
    }
    /**
     * @param array $value
     * @return \CrudGenerator\ConfigManager\ConfigMetadata\DataObject\YamlConfigDataObject
     */
    public function setGenerators(array $value)
    {
        $this->generators = $value;
        return $this;
    }
    /**
     * @param string $generator
     * @param string $question
     * @param string $value
     * @return \CrudGenerator\ConfigManager\ConfigMetadata\DataObject\YamlConfigDataObject
     */
    public function addGeneratorsOptions($generator, $question, $value)
    {
        $this->generatorsOptions[$generator][$question] = $value;
        return $this;
    }
    /**
     * @param MetadataDataObjectConfig $value
     * @return \CrudGenerator\ConfigManager\ConfigMetadata\DataObject\YamlConfigDataObject
     */
    public function setMetadata(MetadataDataObjectConfig $value)
    {
        $this->metadata = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\ConfigManager\ConfigMetadata\DataObject\YamlConfigDataObject
     */
    public function addOptions($value)
    {
        $this->options[] = $value;
        return $this;
    }

    /*
     * GETTERS
     */

    /**
     * @return string
     */
    public function getPackageName()
    {
        return $this->packageName;
    }
    /**
     * @return boolean
     */
    public function getPackageEnabled()
    {
        return $this->packageEnabled;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
    /**
     * @return array
     */
    public function getGenerators()
    {
        return $this->generators;
    }
    /**
     * @return \CrudGenerator\ConfigManager\ConfigMetadata\DataObject\MetadataDataObjectConfig
     */
    public function getMetaData()
    {
        return $this->metadata;
    }
    /**
     * @return array
     */
    public function getGeneratorsOptions()
    {
        return $this->generatorsOptions;
    }
}
