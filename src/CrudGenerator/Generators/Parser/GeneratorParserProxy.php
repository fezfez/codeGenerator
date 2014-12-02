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

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Metadata\DataObject\MetaDataInterface;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorParserProxy implements GeneratorParserInterface
{
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param ContextInterface $context
     */
    public function __construct(ContextInterface $context)
    {
        $this->context = $context;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\GeneratorParserInterface::init()
     */
    public function init(GeneratorDataObject $generator, MetaDataInterface $metadata)
    {
        $generatorParser = GeneratorParserFactory::getInstance($this->context);

        return $generatorParser->init($generator, $metadata);
    }
}
