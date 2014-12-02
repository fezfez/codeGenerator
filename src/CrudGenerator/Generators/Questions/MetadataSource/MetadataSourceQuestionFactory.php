<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Questions\MetadataSource;

use CrudGenerator\Metadata\MetaDataSourceFinderFactory;
use CrudGenerator\Context\ContextInterface;

class MetadataSourceQuestionFactory
{
    /**
     * @param  ContextInterface                                                          $context
     * @return \CrudGenerator\Generators\Questions\MetadataSource\MetadataSourceQuestion
     */
    public static function getInstance(ContextInterface $context)
    {
        $metadataSourceFinder = MetaDataSourceFinderFactory::getInstance();

        return new MetadataSourceQuestion($metadataSourceFinder, $context);
    }
}
