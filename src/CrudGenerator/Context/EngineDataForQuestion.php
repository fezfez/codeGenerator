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

class EngineDataForQuestion
{
    /**
     * @var mixed
     */
    private $uniqueKey = null;
    /**
     * @var mixed
     */
    private $type = null;
    /**
     * @var boolean
     */
    private $shutdownWithoutResponse = false;
    /**
     * @var string
     */
    private $customeExceptionMessage = null;
    /**
     * @var boolean
     */
    private $consumeResponse = false;

    /**
     * @param string $uniqueKey
     */
    public function __construct($uniqueKey)
    {
        $this->uniqueKey = $uniqueKey;
    }

    /**
     * @param  string                                $value
     * @return \CrudGenerator\Context\EngineDataForQuestion
     */
    public function setUniqueKey($value)
    {
        $this->uniqueKey = $value;

        return $this;
    }

    /**
     * @param  string                                $value
     * @return \CrudGenerator\Context\EngineDataForQuestion
     */
    public function setType($value)
    {
        $this->type = $value;

        return $this;
    }

    /**
     * If no response provided, Exception of type
     * CrudGenerator\Generators\ResponseExpectedException
     * will be throw
     *
     * @param boolean $value
     * @param string  $customeExceptionMessage
     *
     * @return \CrudGenerator\Context\EngineDataForQuestion
     */
    public function setShutdownWithoutResponse($value, $customeExceptionMessage = null)
    {
        $this->shutdownWithoutResponse = $value;
        $this->customeExceptionMessage = $customeExceptionMessage;

        return $this;
    }

    /**
     * If true, response will be delete after retrieve
     *
     * @param boolean $value
     *
     * @return \CrudGenerator\Context\EngineDataForQuestion
     */
    public function setConsumeResponse($value)
    {
        $this->consumeResponse = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUniqueKey()
    {
        return $this->uniqueKey;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return boolean
     */
    public function isShutdownWithoutResponse()
    {
        return $this->shutdownWithoutResponse;
    }

    /**
     * @return boolean
     */
    public function isConsumeResponse()
    {
        return $this->consumeResponse;
    }
}
