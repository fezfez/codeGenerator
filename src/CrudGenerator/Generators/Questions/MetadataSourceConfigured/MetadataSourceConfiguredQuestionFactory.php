<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Questions\MetadataSourceConfigured;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\MetaData\Config\MetaDataConfigDAOFactory;

class MetadataSourceConfiguredQuestionFactory
{
    /**
     * @param  ContextInterface                      $context
     * @throws \InvalidArgumentException
     * @return Web\MetaDataSourcesConfiguredQuestion
     */
    public static function getInstance(ContextInterface $context)
    {
        $metadataSourceConfigDAO = MetaDataConfigDAOFactory::getInstance($context);

        return new MetadataSourceConfiguredQuestion($metadataSourceConfigDAO, $context);
    }
}
