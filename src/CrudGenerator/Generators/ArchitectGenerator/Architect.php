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
namespace CrudGenerator\Generators\ArchitectGenerator;

use CrudGenerator\DataObject;

class Architect extends DataObject
{
    protected $generator      = 'CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator';
    /**
     * @var string
     */
    private $generateUnitTest = null;
    /**
     * @var string Target directory
     */
    private $directory       = null;
    /**
     * @var string Target namespace
     */
    private $namespace       = null;
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
     * @return string
     */
    public function getGenerateUnitTest()
    {
        return $this->generateUnitTest;
    }

    /**
     * @param string $value
     * @return \CrudGenerator\Generators\ArchitectGenerator\Artchitect
     */
    public function setGenerateUnitTest($value)
    {
        $this->generateUnitTest = $value;
        return $this;
    }

    /**
     * Set Directory
     * @param string $value
     * @return \CrudGenerator\DataObject
     */
    public function setDirectory($value)
    {
        $this->directory = $value;
        return $this;
    }
    /**
     * Get directory
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
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
     * Get namespace path
     * @return string
     */
    public function getNamespacePath()
    {
        return str_replace('\\', '/', $this->namespace);
    }
}
