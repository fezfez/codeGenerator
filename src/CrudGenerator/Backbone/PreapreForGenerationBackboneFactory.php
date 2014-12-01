<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Backbone;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\Questions\MetadataSourceConfigured\MetadataSourceConfiguredQuestionFactory;
use CrudGenerator\Generators\Questions\Metadata\MetaDataQuestionFactory;
use CrudGenerator\Generators\Questions\Generator\GeneratorQuestionFactory;
use CrudGenerator\Generators\Parser\GeneratorParserProxyFactory;

class PreapreForGenerationBackboneFactory
{
    /**
     * @param  ContextInterface                                     $context
     * @return \CrudGenerator\Backbone\PreapreForGenerationBackbone
     */
    public static function getInstance(ContextInterface $context)
    {
        return new PreapreForGenerationBackbone(
            MetadataSourceConfiguredQuestionFactory::getInstance($context),
            MetaDataQuestionFactory::getInstance($context),
            GeneratorQuestionFactory::getInstance($context),
            GeneratorParserProxyFactory::getInstance($context)
        );
    }
}
