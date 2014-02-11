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
namespace CrudGenerator\GeneratorsEmbed\CrudGenerator;

use CrudGenerator\DataObject;

class Crud extends DataObject
{
    /**
     * @var boolean
     */
    protected $writeAction    = null;
    /**
     * @var string Prefix for routes
     */
    protected $prefixRouteName = null;
    /**
     * @var string Controller name
     */
    protected $controllerName = null;
    /**
     * @var string Metadata name to display
     */
    protected $displayName = null;
    /**
     * @var string Metadata name to display
     */
    protected $displayNames = null;
    /**
     * @var string Model namespace
     */
    protected $modelNamespace = null;
    /**
     * @var array
     */
    protected $attributesDisplayName = array();
    /**
     * @var string
     */
    private $viewDirectory = null;
    /**
     * @var string
     */
    private $controllerDirectory = null;

    /**
     * @param string $attribute
     * @param string $name
     * @return \CrudGenerator\GeneratorsEmbed\CrudGenerator\Crud
     */
    public function setAttributeName($attribute, $name)
    {
        $this->attributesDisplayName[$attribute] = $name;
        return $this;
    }
    /**
     * @param boolean $value
     * @return \CrudGenerator\GeneratorsEmbed\CrudGenerator\Crud
     */
    public function setWriteAction($value)
    {
        $this->writeAction = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\GeneratorsEmbed\CrudGenerator\Crud
     */
    public function setDisplayName($value)
    {
        $this->displayName = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\GeneratorsEmbed\CrudGenerator\Crud
     */
    public function setPrefixRouteName($value)
    {
        $this->prefixRouteName = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\GeneratorsEmbed\CrudGenerator\Crud
     */
    public function setDisplayNames($value)
    {
        $this->displayNames = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\GeneratorsEmbed\CrudGenerator\Crud
     */
    public function setControllerName($value)
    {
        $this->controllerName = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\GeneratorsEmbed\CrudGenerator\Crud
     */
    public function setViewDirectory($value)
    {
        $this->viewDirectory = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\GeneratorsEmbed\CrudGenerator\Crud
     */
    public function setControllerDirectory($value)
    {
        $this->controllerDirectory = $value;
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
        return '';
    }
    /**
     * Get controller path
     * @return string
     */
    public function getViewDirectory()
    {
        return $this->viewDirectory;
    }
    /**
     * Get view path
     * @return string
     */
    public function getControllerDirectory()
    {
        return $this->controllerDirectory;
    }
}
