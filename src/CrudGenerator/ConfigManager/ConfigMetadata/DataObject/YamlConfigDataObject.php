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

use CrudGenerator\ConfigManager\ConfigMetadata\DataObject\PropertiesCollection;

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
     * @var PropertiesCollection propertiesCollection
     */
    private $propertiesCollection = null;

    /**
     * @var array generators
     */
    private $generators           = null;

    /*
     * SETTERS
     */

    public function setPackageName($value)
    {
        $this->packageName = $value;
        return $this;
    }

    public function setPackageEnabled($value)
    {
        $this->packageEnabled = $value;
        return $this;
    }

    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }

    public function setOptions($value)
    {
        $this->options = $value;
        return $this;
    }


    public function addOptions($value)
    {
        $this->options[] = $value;
        return $this;
    }

    public function setPropertiesCollection($value)
    {
        $this->propertiesCollection = $value;
        return $this;
    }

    public function addPropertiesCollection($value)
    {
        $this->propertiesCollection->append($value);
        return $this;
    }

    public function setGenerators($value)
    {
        $this->generators = $value;
        return $this;
    }

    /*
     * GETTERS
     */

    public function getPackageName()
    {
        return $this->packageName;
    }

    public function getPackageEnabled()
    {
        return $this->packageEnabled;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getPropertiesCollection()
    {
        return $this->propertiesCollection;
    }

    public function getGenerators()
    {
        return $this->generators;
    }
}
