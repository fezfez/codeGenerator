<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Parser\Lexical\QuestionType;

class QuestionTypeCollection implements \IteratorAggregate
{
    /**
     * @var \ArrayIterator
     */
    private $collection = null;

    public function __construct()
    {
        $this->collection = new \ArrayIterator(array());
    }

    /**
     * @param  QuestionTypeInterface                                                        $value
     * @return \CrudGenerator\Generators\Parser\Lexical\QuestionType\QuestionTypeCollection
     */
    public function append(QuestionTypeInterface $value)
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
}
