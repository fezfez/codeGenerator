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
namespace CrudGenerator\GeneratorsEmbed\ArchitectGenerator;

use CrudGenerator\DataObject;

class Architect extends DataObject implements \JsonSerializable
{
    /**
     * @var string Target directory
     */
    private $modelDirectory  = null;
    /**
     * @var string Target directory
     */
    private $unitTestDirectory  = null;
    /**
     * @var string Target namespace
     */
    private $namespace       = null;
    /**
     * @var string Model name
     */
    private $modelName       = null;
    /**
     * @var array
     */
    private $attributesDisplayName = array();

    /**
     * Set model directory
     * @param string $value
     * @return \CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect
     */
    public function setModelDirectory($value)
    {
        $this->modelDirectory = $value;
        return $this;
    }
    /**
     * Set model directory
     * @param string $value
     * @return \CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect
     */
    public function setUnitTestDirectory($value)
    {
        $this->unitTestDirectory = $value;
        return $this;
    }
    /**
     * @param string $attribute
     * @param string $name
     * @return \CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect
     */
    public function setAttributeName($attribute, $name)
    {
        $this->attributesDisplayName[$attribute] = $name;
        return $this;
    }
    /**
     * Set Namespace
     * @param string $value
     * @return \CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect
     */
    public function setNamespace($value)
    {
        $this->namespace = $value;
        return $this;
    }
    /**
     * Set ModelName
     * @param string $value
     * @return \CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect
     */
    public function setModelName($value)
    {
        $this->modelName = $value;
        return $this;
    }

    /**
     * Get model directory
     * @return string
     */
    public function getModelDirectory()
    {
        return $this->modelDirectory;
    }
    /**
     * Get unit test directory
     * @return string
     */
    public function getUnitTestDirectory()
    {
        return $this->unitTestDirectory;
    }
    /**
     * @param string $attribute
     * @return string|null
     */
    public function getAttributeName($attribute = null)
    {
        if ($attribute === null) {
            return $this->attributesDisplayName;
        } else {
            return (isset($this->attributesDisplayName[$attribute])) ? $this->attributesDisplayName[$attribute] : null;
        }
    }
    /**
     * Set Namespace
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
    /**
     * Set ModelName
     * @return string
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /* (non-PHPdoc)
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(),
            array(
                'modelDirectory'        => $this->modelDirectory,
                'unitTestDirectory'     => $this->unitTestDirectory,
                'namespace'             => $this->namespace,
                'modelName'             => $this->modelName,
                'attributesDisplayName' => $this->attributesDisplayName,
            )
        );
    }
}
