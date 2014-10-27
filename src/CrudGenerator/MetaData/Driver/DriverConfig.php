<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\MetaData\Driver;

use CrudGenerator\MetaData\Config\MetaDataConfigDAO;
use KeepUpdate\Annotations;

/**
 * @author Stéphane Demonchaux
 *
 * @Annotations\Synchronizer(strict=true);
 */
class DriverConfig implements \JsonSerializable
{
    const QUESTION_DESCRIPTION = 'desc';
    const QUESTION_ATTRIBUTE = 'attr';
    const SOURCE_FACTORY = 'metadataDaoFactory';
    const FACTORY = 'driver';
    const RESPONSE = 'response';
    const UNIQUE_NAME = 'uniqueName';
    /**
     * @var array
     */
    private $question = array();
    /**
     * @var array
     */
    private $response = array();
    /**
     *
     * @Annotations\PlainTextClassImplements(interface="CrudGenerator\MetaData\Driver\DriverFactoryInterface", nullable=false)
     * @var string
     */
    private $driver = null;
    /**
     * @var string
     */
    private $uniqueName = null;
    /**
     * @Annotations\PlainTextClassImplements(interface="CrudGenerator\MetaData\Sources\MetaDataDAOFactoryInterface", nullable=false)
     * @var string
     */
    private $metadataDaoFactory = null;

    /**
     * @param string $uniqueName
     */
    public function __construct($uniqueName)
    {
        $this->uniqueName = $uniqueName;
    }

    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\Driver\DriverConfig
     */
    public function setDriver($value)
    {
        if (is_string($value) === false) {
            throw new \Exception('Must be a string');
        } elseif (false === class_exists($value, true)) {
            throw new \Exception('Class does not exist');
        } elseif (in_array('CrudGenerator\MetaData\Driver\DriverFactoryInterface', class_implements($value)) === false) {
            throw new \Exception('Wrong implementation');
        }

        $this->driver = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\Driver\DriverConfig
     */
    public function setMetadataDaoFactory($value)
    {
        if (is_string($value) === false) {
            throw new \Exception('Must be a string');
        } elseif (false === class_exists($value, true)) {
            throw new \Exception('Class does not exist');
        } elseif (in_array('CrudGenerator\MetaData\Sources\MetaDataDAOFactoryInterface', class_implements($value)) === false) {
            throw new \Exception('Wrong implementation');
        }

        $this->metadataDaoFactory = $value;

        return $this;
    }

    /**
     * @param string $attribute
     * @param string $response
     * @return \CrudGenerator\MetaData\Driver\DriverConfig
     */
    public function response($attribute, $response)
    {
        $this->response[$attribute] = $response;

        return $this;
    }

    /**
     * @param string $description
     * @param string $attribute
     * @return \CrudGenerator\MetaData\Driver\DriverConfig
     */
    public function addQuestion($description, $attribute)
    {
        $this->question[] = array(self::QUESTION_DESCRIPTION => $description, self::QUESTION_ATTRIBUTE => $attribute);
        return $this;
    }

    /**
     * @return array
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param string $attribute
     * @return string|null
     */
    public function getResponse($attribute)
    {
        return (isset($this->response[$attribute]) === true) ? $this->response[$attribute] : null;
    }

    /**
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @return string
     */
    public function getUniqueName()
    {
        return $this->uniqueName;
    }

    /**
     * @return string
     */
    public function getMetadataDaoFactory()
    {
        return $this->metadataDaoFactory;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array(
            self::SOURCE_FACTORY => $this->metadataDaoFactory,
            self::FACTORY        => $this->driver,
            self::RESPONSE       => $this->response,
            self::UNIQUE_NAME    => $this->uniqueName
        );
    }
}
