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

use CrudGenerator\Generators\Parser\Lexical\QuestionResponseTypeEnum;

class SimpleQuestion
{
    /**
     * @var mixed
     */
    private $text = null;
    /**
     * @var mixed
     */
    private $uniqueKey = null;
    /**
     * @var mixed
     */
    private $defaultResponse = null;
    /**
     * @var mixed
     */
    private $required = false;
    /**
     * @var mixed
     */
    private $helpMessage = null;
    /**
     * @var mixed
     */
    private $type = null;
    /**
     * @var QuestionResponseTypeEnum
     */
    private $responseType = null;
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
     * @param string $text
     * @param string $uniqueKey
     */
    public function __construct($text, $uniqueKey)
    {
        $this->text      = $text;
        $this->uniqueKey = $uniqueKey;
    }

    /**
     * @param  string                                $value
     * @return \CrudGenerator\Context\SimpleQuestion
     */
    public function setText($value)
    {
        $this->text = $value;

        return $this;
    }

    /**
     * @param  string                                $value
     * @return \CrudGenerator\Context\SimpleQuestion
     */
    public function setUniqueKey($value)
    {
        $this->uniqueKey = $value;

        return $this;
    }

    /**
     * @param  string|null                           $value
     * @return \CrudGenerator\Context\SimpleQuestion
     */
    public function setDefaultResponse($value)
    {
        $this->defaultResponse = $value;

        return $this;
    }

    /**
     * @param  mixed                                 $value
     * @return \CrudGenerator\Context\SimpleQuestion
     */
    public function setRequired($value)
    {
        $this->required = $value;

        return $this;
    }

    /**
     * @param  string                                $value
     * @return \CrudGenerator\Context\SimpleQuestion
     */
    public function setHelpMessage($value)
    {
        $this->helpMessage = $value;

        return $this;
    }

    /**
     * @param  string                                $value
     * @return \CrudGenerator\Context\SimpleQuestion
     */
    public function setType($value)
    {
        $this->type = $value;

        return $this;
    }

    /**
     * @param  QuestionResponseTypeEnum              $value
     * @return \CrudGenerator\Context\SimpleQuestion
     */
    public function setResponseType(QuestionResponseTypeEnum $value)
    {
        $this->responseType = $value;

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
     * @return \CrudGenerator\Context\SimpleQuestion
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
     * @return \CrudGenerator\Context\SimpleQuestion
     */
    public function setConsumeResponse($value)
    {
        $this->consumeResponse = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
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
    public function getDefaultResponse()
    {
        return $this->defaultResponse;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return mixed
     */
    public function getHelpMessage()
    {
        return $this->helpMessage;
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
