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
namespace CrudGenerator;

use CrudGenerator\MetaData\DataObject\MetaDataDataObject;

/**
 * Base representation for template generation
 *
 * @author Stéphane Demonchaux
 */
abstract class DataObject
{
    /**
     * @var string Module name
     */
    private $module          = null;
    /**
     * @var string Entity name
     */
    private $entity          = null;
    /**
     * @var MetaDataDataObject Metadata object
     */
    private $metadata        = null;
    /**
     * @var string Target namespace
     */
    private $namespace       = null;
    /**
     * @var string Target directory
     */
    private $directory       = null;
    /**
     * @var string
     */
    private $generator       = null;

    /**
     * Set Module
     * @param string $value
     * @return \CrudGenerator\DataObject
     */
    public function setModule($value)
    {
        $this->module = $value;
        return $this;
    }
    /**
     * Set Entity
     * @param string $value
     * @return \CrudGenerator\DataObject
     */
    public function setEntity($value)
    {
        $this->entity = $value;
        return $this;
    }
    /**
     * Set MetaData
     * @param MetaDataDataObject $value
     * @return \CrudGenerator\DataObject
     */
    public function setMetadata(MetaDataDataObject $value)
    {
        $this->metadata = $value;
        return $this;
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
     * Set Generator
     * @param string $value
     * @return \CrudGenerator\DataObject
     */
    public function setGenerator($value)
    {
        $this->generator = $value;
        return $this;
    }

    /**
     * Get Module
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }
    /**
     * Get Module name
     * @return string
     */
    public function getModuleName()
    {
        return $this->module;
    }
    /**
     * Get Entity
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }
    /**
     * Get Entity name
     * @return string
     */
    public function getEntityName()
    {
        if (!strrchr($this->entity, '\\')) {
            return $this->entity;
        } else {
            return str_replace('\\', '', strrchr($this->entity, '\\'));
        }
    }
    /**
     * Get MetaData
     * @return \CrudGenerator\MetaData\DataObject\MetaDataDataObject
     */
    public function getMetadata()
    {
        return $this->metadata;
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
     * Get generator
     *
     * @return string
     */
    public function getGenerator()
    {
        return $this->generator;
    }
    /**
     * Get controller path
     * @return string
     */
    public function getControllerPath()
    {
        return $this->getModule() . '/src/' . $this->getModuleName() . '/Controller/';
    }
    /**
     * Get view path
     * @return string
     */
    public function getViewPath()
    {
        return $this->getModule() . '/view/' . $this->getModuleName() . '/' . $this->getEntityName();
    }
}
