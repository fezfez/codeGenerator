<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Context;

/**
 * Represntation of a predefined response
 *
 */
class PredefinedResponse
{
    /**
     * @var string
     */
    private $id = null;
    /**
     * @var string
     */
    private $label = null;
    /**
     * @var mixed
     */
    private $response = null;
    /**
     * @var array
     */
    private $additionalData = array();

    /**
     * @param string $id
     * @param string $label
     * @param mixed  $response
     */
    public function __construct($id, $label, $response)
    {
        $this->id       = $id;
        $this->label    = $label;
        $this->response = $response;
    }
    /**
     * The response Identification
     *
     * @param  string                                    $value
     * @return \CrudGenerator\Context\PredefinedResponse
     */
    public function setId($value)
    {
        $this->id = $value;

        return $this;
    }

    /**
     * The response text
     *
     * @param  string                                    $value
     * @return \CrudGenerator\Context\PredefinedResponse
     */
    public function setLabel($value)
    {
        $this->label = $value;

        return $this;
    }
    /**
     * If the user choice this response, this value would be returned
     *
     * @param  mixed                                     $value
     * @return \CrudGenerator\Context\PredefinedResponse
     */
    public function setResponse($value)
    {
        $this->response = $value;

        return $this;
    }
    /**
     * Additional data will be added only in WebContext
     *
     * @param  array                                     $value
     * @return \CrudGenerator\Context\PredefinedResponse
     */
    public function setAdditionalData(array $value)
    {
        $this->additionalData = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return array
     */
    public function getAdditionalData()
    {
        return $this->additionalData;
    }
}
