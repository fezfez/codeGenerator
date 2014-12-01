<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Parser;

use CrudGenerator\Generators\Parser\Lexical\ParserInterface;

class ParserCollection
{
    /**
     * @var \ArrayObject
     */
    private $preParser = null;
    /**
     * @var \ArrayObject
     */
    private $postParser = null;

    public function __construct()
    {
        $this->preParser  = new \ArrayObject();
        $this->postParser = new \ArrayObject();
    }

    /**
     * @param  ParserInterface                                   $value
     * @return \CrudGenerator\Generators\Parser\ParserCollection
     */
    public function addPreParse(ParserInterface $value)
    {
        $this->preParser->append($value);

        return $this;
    }

    /**
     * @param  ParserInterface                                   $value
     * @return \CrudGenerator\Generators\Parser\ParserCollection
     */
    public function addPostParse(ParserInterface $value)
    {
        $this->postParser->append($value);

        return $this;
    }

    /**
     * @return \ArrayObject
     */
    public function getPreParse()
    {
        return $this->preParser;
    }

    /**
     * @return \ArrayObject
     */
    public function getPostParse()
    {
        return $this->postParser;
    }
}
