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

class QuestionWithPredefinedResponse extends SimpleQuestion
{
    /**
     * @var PredefinedResponseCollection
     */
    private $predefinedResponseCollection = null;
    /**
     * @var string
     */
    private $preselectedResponse = null;

    /**
     * @param string                       $text
     * @param string                       $uniqueKey
     * @param PredefinedResponseCollection $predefinedResponseCollection
     */
    public function __construct($text, $uniqueKey, PredefinedResponseCollection $predefinedResponseCollection)
    {
        parent::__construct($text, $uniqueKey);
        $this->predefinedResponseCollection = $predefinedResponseCollection;
    }

    /**
     * If this preselected reponse match with
     * a response id this reponse will be automatically
     * selected
     *
     * @param  string                         $value
     * @return QuestionWithPredefinedResponse
     */
    public function setPreselectedResponse($value)
    {
        $this->preselectedResponse = $value;

        return $this;
    }

    /**
     * @return PredefinedResponseCollection
     */
    public function getPredefinedResponseCollection()
    {
        return $this->predefinedResponseCollection;
    }

    /**
     * @return string
     */
    public function getPreselectedResponse()
    {
        return $this->preselectedResponse;
    }
}
