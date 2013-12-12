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
namespace CrudGenerator\Generators\CrudGenerator;

use CrudGenerator\DataObject;

class Crud extends DataObject
{
    /**
     * @var string
     */
    protected $generator      = 'CrudGenerator\Generators\CrudGenerator\CrudGenerator';
    /**
     * @var boolean
     */
    protected $writeAction    = null;
    /**
     * @var string
     */
    protected $prefixRouteName = null;
    /**
     * @var string
     */
    protected $controllerName = null;
    /**
     * @var string
     */
    protected $displayName = null;
    /**
     * @var array
     */
    protected $displayNames = null;
    /**
     * @var array
     */
    protected $modelNamespace = null;
    /**
     * @var array
     */
    protected $attributesDisplayName = array();

    /**
     * @param string $attribute
     * @param string $name
     * @return \CrudGenerator\Generators\CrudGenerator\Crud
     */
    public function setAttributeName($attribute, $name)
    {
        $this->attributesDisplayName[$attribute] = $name;
        return $this;
    }
    /**
     * @param boolean $value
     * @return \CrudGenerator\Generators\ArchitectGenerator\Artchitect
     */
    public function setWriteAction($value)
    {
        $this->writeAction = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\Generators\ArchitectGenerator\Artchitect
     */
    public function setDisplayName($value)
    {
        $this->displayName = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\Generators\ArchitectGenerator\Artchitect
     */
    public function setPrefixRouteName($value)
    {
        $this->prefixRouteName = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\Generators\ArchitectGenerator\Artchitect
     */
    public function setDisplayNames($value)
    {
        $this->displayNames = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\Generators\ArchitectGenerator\Artchitect
     */
    public function setControllerName($value)
    {
        $this->controllerName = $value;
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
     * @return boolean
     */
    public function getWriteAction()
    {
        return $this->writeAction;
    }
    /**
     * @return string
     */
    public function getPrefixRouteName()
    {
        return $this->prefixRouteName;
    }
    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
    /**
     * @return string
     */
    public function getDisplayNames()
    {
        return $this->displayNames;
    }
    /**
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }
    /**
     * @return string
     */
    public function getControllerNamespace()
    {
        return $this->getModuleName();
    }
    /**
     * Get controller path
     * @return string
     */
    public function findControllerPath()
    {
        return $this->getModule() . '/src/' . $this->getControllerNamespace() . '/Controller/';
    }
    /**
     * Get view path
     * @return string
     */
    public function findViewPath()
    {
        return $this->getModule() . '/view/' .  strtolower($this->getControllerNamespace()) . '/' . strtolower(preg_replace("[A-Z]", "-\$1", $this->getControllerName())) . '/';
    }
}
