<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Questions\Metadata;

use CrudGenerator\MetaData\MetaDataSourceFactory;
use CrudGenerator\Context\ContextInterface;

class MetadataQuestionFactory
{
    /**
     * @param  ContextInterface                                              $context
     * @return \CrudGenerator\Generators\Questions\Metadata\MetaDataQuestion
     */
    public static function getInstance(ContextInterface $context)
    {
        return new MetaDataQuestion(new MetaDataSourceFactory(), $context);
    }
}
