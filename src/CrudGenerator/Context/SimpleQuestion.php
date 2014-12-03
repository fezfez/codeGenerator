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

class SimpleQuestion extends EngineDataForQuestion
{
    /**
     * @var mixed
     */
    private $text = null;
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
     * @var QuestionResponseTypeEnum
     */
    private $responseType = null;

    /**
     * @param string $text
     * @param string $uniqueKey
     */
    public function __construct($text, $uniqueKey)
    {
        $this->text = $text;

        parent::__construct($uniqueKey);
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
     * @param  QuestionResponseTypeEnum              $value
     * @return \CrudGenerator\Context\SimpleQuestion
     */
    public function setResponseType(QuestionResponseTypeEnum $value)
    {
        $this->responseType = $value;

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
     * @return \CrudGenerator\Generators\Parser\Lexical\QuestionResponseTypeEnum
     */
    public function getResponseType()
    {
        return $this->responseType;
    }
}
