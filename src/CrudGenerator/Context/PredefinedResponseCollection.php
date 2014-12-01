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

class PredefinedResponseCollection implements \IteratorAggregate
{
    /**
     * @var \ArrayIterator
     */
    private $collection = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->collection = new \ArrayIterator(array());
    }
    /**
     * @param  PredefinedResponse                                  $value
     * @return \CrudGenerator\Context\PredefinedResponseCollection
     */
    public function append(PredefinedResponse $value)
    {
        $this->collection->append($value);

        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->collection;
    }

    /**
     * @param  string                      $idInSearch
     * @throws PredefinedResponseException
     * @return PredefinedResponse
     */
    public function offsetGetById($idInSearch)
    {
        if ($idInSearch !== null) {
            foreach ($this->collection as $question) {
                /* @var $question PredefinedResponse */
                if ($idInSearch === $question->getId()) {
                    return $question;
                }
            }
        }

        throw new PredefinedResponseException(sprintf('Response with id "%s" not found', $idInSearch));
    }

    /**
     * @param  string                      $labelInSearch
     * @throws PredefinedResponseException
     * @return PredefinedResponse
     */
    public function offsetGetByLabel($labelInSearch)
    {
        foreach ($this->collection as $question) {
            /* @var $question PredefinedResponse */
            if ($labelInSearch === $question->getLabel()) {
                return $question;
            }
        }

        throw new PredefinedResponseException(sprintf('Response with label "%s" not found', $labelInSearch));
    }
}
