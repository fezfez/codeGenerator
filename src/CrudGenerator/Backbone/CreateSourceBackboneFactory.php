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
use CrudGenerator\Generators\Questions\MetadataSource\MetadataSourceQuestionFactory;
use CrudGenerator\Metadata\Config\MetaDataConfigDAOFactory;

class CreateSourceBackboneFactory
{
    /**
     * Create CreateSourceBackbone instance
     *
     * @param  ContextInterface                             $context
     * @return \CrudGenerator\Backbone\CreateSourceBackbone
     */
    public static function getInstance(ContextInterface $context)
    {
        return new CreateSourceBackbone(
            MetadataSourceQuestionFactory::getInstance($context),
            MetaDataConfigDAOFactory::getInstance($context),
            $context
        );
    }
}
