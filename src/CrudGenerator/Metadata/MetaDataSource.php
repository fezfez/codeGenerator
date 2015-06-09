<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata;

use CrudGenerator\Metadata\Driver\Driver;
use CrudGenerator\Metadata\Driver\DriverConfig;
use KeepUpdate\Annotations;

/**
 * Adapter representation
 * @author Stéphane Demonchaux
 *
 * @Annotations\Synchronizer(strict=true);
 */
class MetaDataSource implements \JsonSerializable
{
    /**
     * @var string
     */
    const CONFIG = 'config';
    /**
     * @var string
     */
    const DEFINITION = 'definition';
    /**
     * @var unknown
     */
    const METADATA_DAO = 'metaDataDAO';
    /**
     * @var string
     */
    const METADATA_DAO_FACTORY = 'metaDataDAOFactory';
    /**
     * @var unknown
     */
    const FALSE_DEPENDENCIES = 'falseDependencies';
    /**
     * @var string
     */
    const UNIQUE_NAME = 'uniqueName';

    /**
     * @var string name of adapater
     */
    private $metaDataDAO = null;
    /**
     * @Annotations\PlainTextClassImplements(
     *     interface="CrudGenerator\Metadata\Sources\MetaDataDAOFactoryInterface", nullable=false
     * )
     *
     * @var string name of adapater
     */
    private $metaDataDAOFactory = null;
    /**
     * @var string true if dependencies of adapater are complete
     */
    private $falseDependencies = null;
    /**
     * @var string adapter definition
     */
    private $definition = null;
    /**
     * @Annotations\Chain(class="CrudGenerator\Metadata\Driver\DriverConfig", nullable=true)
     *
     * @var DriverConfig Driver configuration
     */
    private $config = null;
    /**
     * Collection of connector
     *
     * @var array
     */
    private $driversDescription = array();
    /**
     * Unique name
     *
     * @var string
     */
    private $uniqueName = null;

    /**
     * Set name
     * @param  string                                 $value
     * @return \CrudGenerator\Metadata\MetaDataSource
     */
    public function setMetadataDao($value)
    {
        if (is_string($value) === false) {
            throw new \Exception('"MetadataDao" Must be a string');
        } elseif (false === class_exists($value, true)) {
            throw new \Exception(sprintf('Class "%s" does not exist', $value));
        } elseif (
            in_array('CrudGenerator\Metadata\Sources\MetaDataDAOInterface', class_implements($value)) === false
        ) {
            throw new \Exception(
                sprintf(
                    'Class "%s" does not implement CrudGenerator\Metadata\Sources\MetaDataDAOInterface',
                    $value
                )
            );
        }
        $this->metaDataDAO = $value;

        return $this;
    }
    /**
     * @param  string                                 $value
     * @return \CrudGenerator\Metadata\MetaDataSource
     */
    public function setMetadataDaoFactory($value)
    {
        if (is_string($value) === false) {
            throw new \Exception('"MetadataDaoFactory" Must be a string');
        } elseif (false === class_exists($value, true)) {
            throw new \Exception(sprintf('Class "%s" does not exist', $value));
        } elseif (
            in_array('CrudGenerator\Metadata\Sources\MetaDataDAOFactoryInterface', class_implements($value)) === false
        ) {
            throw new \Exception(
                sprintf(
                    'Class "%s" does not implement CrudGenerator\Metadata\Sources\MetaDataDAOFactoryInterface',
                    $value
                )
            );
        }

        $this->metaDataDAOFactory = $value;

        return $this;
    }
    /**
     * Set definition
     * @param  string                                 $value
     * @return \CrudGenerator\Metadata\MetaDataSource
     */
    public function setDefinition($value)
    {
        $this->definition = $value;

        return $this;
    }
    /**
     * Set false dependencie
     * @param  string                                 $value
     * @return \CrudGenerator\Metadata\MetaDataSource
     */
    public function setFalseDependencie($value)
    {
        $this->falseDependencies = $value;

        return $this;
    }
    /**
     * Set config
     * @param  DriverConfig                           $value
     * @return \CrudGenerator\Metadata\MetaDataSource
     */
    public function setConfig(DriverConfig $value)
    {
        $this->config = $value;

        return $this;
    }
    /**
     * @param  Driver                                 $value
     * @return \CrudGenerator\Metadata\MetaDataSource
     */
    public function addDriverDescription(Driver $value)
    {
        $this->driversDescription[] = $value;

        return $this;
    }
    /**
     * Set unique name
     * @param  string                                 $value
     * @return \CrudGenerator\Metadata\MetaDataSource
     */
    public function setUniqueName($value)
    {
        $this->uniqueName = $value;

        return $this;
    }

    /**
     * Get MetadataDAO class as string
     * @return string
     */
    public function getMetadataDao()
    {
        return $this->metaDataDAO;
    }
    /**
     * Get MetadataDAOFactory class as string
     * @return string
     */
    public function getMetadataDaoFactory()
    {
        return $this->metaDataDAOFactory;
    }
    /**
     * Get definition
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }
    /**
     * Get false dependencies
     * @return string
     */
    public function getFalseDependencies()
    {
        return $this->falseDependencies;
    }
    /**
     * Get config
     *
     * @return \CrudGenerator\Metadata\Driver\DriverConfig
     */
    public function getConfig()
    {
        return $this->config;
    }
    /**
     * @return string
     */
    public function getUniqueName()
    {
        if ($this->config === null) {
            return $this->definition;
        } else {
            return $this->uniqueName . ' ' . $this->config->getUniqueName();
        }
    }

    /**
     * getDriversDescription
     *
     * @return array
     */
    public function getDriversDescription()
    {
        return $this->driversDescription;
    }
    /**
     * @return boolean
     */
    public function isUniqueDriver()
    {
        return (count($this->driversDescription) === 1) ? true : false;
    }

    /* (non-PHPdoc)
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return array(
            self::CONFIG               => $this->config,
            self::DEFINITION           => $this->definition,
            self::METADATA_DAO         => $this->metaDataDAO,
            self::METADATA_DAO_FACTORY => $this->metaDataDAOFactory,
            self::FALSE_DEPENDENCIES   => $this->falseDependencies
        );
    }
}
