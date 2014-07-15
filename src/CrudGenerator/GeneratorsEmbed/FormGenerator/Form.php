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
namespace CrudGenerator\GeneratorsEmbed\FormGenerator;

use CrudGenerator\DataObject;

class Form extends DataObject
{
    /**
     * @var string Target directory
     */
    private $modelName       = null;
    /**
     * @var string Target namespace
     */
    private $namespace       = null;
    /**
     * @var array
     */
    private $attributesDisplayName = array();
    /**
     * @var string
     */
    private $formDirectory = null;

    /**
     * @param string $attribute
     * @param string $name
     * @return Form
    */
    public function setAttributeName($attribute, $name)
    {
        $this->attributesDisplayName[$attribute] = $name;
        return $this;
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
     * Set Directory
     * @param string $value
     * @return \CrudGenerator\DataObject
     */
    public function setModelName($value)
    {
        $this->modelName = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return Form
     */
    public function setFormDirectory($value)
    {
        $this->formDirectory = $value;
        return $this;
    }
    /**
     * Get directory
     *
     * @return string
     */
    public function getModelName()
    {
        return $this->modelName;
    }
    /**
     * Set Namespace
     * @param string $value
     * @return \CrudGenerator\DataObject
     */
    public function setNamespace($value)
    {
        $this->namespace = $value;
        return $this;
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
     * Get view path
     * @return string
     */
    public function getFormDirectory()
    {
        return $this->formDirectory;
    }

    /* (non-PHPdoc)
     * @see JsonSerializable::jsonSerialize()
    */
    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(),
            array(
                'formDirectory'         => $this->formDirectory,
                'namespace'             => $this->namespace,
                'modelName'             => $this->modelName,
                'attributesDisplayName' => $this->attributesDisplayName
            )
        );
    }
}
