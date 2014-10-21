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

/**
 * @author Stéphane Demonchaux
 */
class DriverConfig implements \JsonSerializable
{
    /**
     * @var array
     */
    private $question = array();
    /**
     * @var array
     */
    private $response = array();
    /**
     * @var string
     */
    private $driver = null;
    /**
     * @var string
     */
    private $uniqueName = null;
    /**
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
        $this->driver = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\Driver\DriverConfig
     */
    public function setMetadataDaoFactory($value)
    {
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
        $this->question[] = array('desc' => $description, 'attr' => $attribute);
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
            MetaDataConfigDAO::SOURCE_FACTORY_KEY => $this->metadataDaoFactory,
            MetaDataConfigDAO::DRIVER_FACTORY_KEY => $this->driver,
            MetaDataConfigDAO::RESPONSE_KEY       => $this->response,
            MetaDataConfigDAO::UNIQUE_NAME_KEY    => $this->uniqueName
        );
    }
}
