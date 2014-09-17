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
namespace CrudGenerator\Generators;

use CrudGenerator\DataObject;
use CrudGenerator\MetaData\MetaDataSource;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */

class GeneratorDataObject implements \JsonSerializable
{
    /**
     * @var DataObject
     */
    private $dto = null;
    /**
     * @var string
     */
    private $name = null;
    /**
     * @var MetaDataSource
     */
    private $metaDataSource = null;
    /**
     * @var string
     */
    private $path = null;
    /**
     * @var array
     */
    private $environnement = array();
    /**
     * @var array
     */
    private $files = array();
    /**
     * @var array
     */
    private $directories = array();
    /**
     * @var array
     */
    private $templateVariable = array();
    /**
     * @var array
     */
    private $dependecies = array();

    /**
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    public function setDto(DataObject $value)
    {
        $this->dto = $value;
        return $this;
    }
    /**
     * @param string $name
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    /**
     * @param MetaDataSource $metaDataSource
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    public function setMetadataSource(MetaDataSource $metaDataSource)
    {
        $this->metaDataSource = $metaDataSource;
        return $this;
    }
    /**
     * @param string $name
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    public function setPath($name)
    {
        $this->path = realpath(dirname($name));
        return $this;
    }

    /**
     * @param string $environnement
     * @param string $value
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    public function addEnvironnementValue($environnement, $value)
    {
        if (empty($this->dto) === true) {
            throw new \LogicException('DTO cant be empty');
        }

        $this->dto->addEnvironnementValue($environnement, $value);
        foreach ($this->dependecies as $dependency) {
            $dependency->addEnvironnementValue($environnement, $value);
        }
        $this->environnement[$environnement] = $value;
        return $this;
    }
    /**
     * @param GeneratorDataObject $generator
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    public function addDependency(GeneratorDataObject $generator)
    {
        $this->dependecies[] = $generator;
        return $this;
    }
    /**
     * @param string $skeletonPath
     * @param string $name
     * @param string $value
     * @param string $result
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    public function addFile($skeletonPath, $name, $value, $result = null)
    {
        $this->files[$value] = array(
            'skeletonPath' => $skeletonPath,
            'fileName'     => $value,
            'name'         => $name,
            'isWritable'   => is_writable(dirname($value))
        );

        if (null !== $result) {
            $this->files[$value]['result'] = $result;
        }

        return $this;
    }
    /**
     * @param string $name
     * @param string $value
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    public function addDirectories($name, $value)
    {
        $this->directories[$name] = $value;
        return $this;
    }
    /**
     * @param string $name
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    public function addTemplateVariable($name, $value)
    {
        $this->templateVariable[$name] = $value;
        return $this;
    }
    /**
     * @return DataObject
     */
    public function getDto()
    {
        return $this->dto;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @return \CrudGenerator\MetaData\MetaDataSource
     */
    public function getMetadataSource()
    {
        return $this->metaDataSource;
    }
    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
    /**
     * @return null|string
     */
    public function getEnvironnement($name)
    {
        return (isset($this->environnement[$name]) === true) ? $this->environnement[$name] : null;
    }
    /**
     * @return array
     */
    public function getEnvironnementCollection()
    {
        return $this->environnement;
    }
    /**
     * @return string
     */
    public function getFiles()
    {
        return $this->files;
    }
    /**
     * @return array
     */
    public function getDirectories()
    {
        return $this->directories;
    }
    /**
     * @return array
     */
    public function getTemplateVariables()
    {
        return $this->templateVariable;
    }
    /**
     * @return array
     */
    public function getDependencies()
    {
        return $this->dependecies;
    }

    /**
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    public function deleteFile($value)
    {
        unset($this->files[$value]);
        return $this;
    }

    /* (non-PHPdoc)
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return array(
            'metaDataSource'   => $this->metaDataSource,
            'templateVariable' => $this->templateVariable,
            'files'            => $this->getFiles(),
            'directories'      => $this->directories,
            'name'             => $this->name,
            'environnement'    => $this->environnement,
            'dependencies'     => $this->dependecies,
            'dto'              => $this->dto,
            'dtoClass'         => get_class($this->dto)
        );
    }
}
