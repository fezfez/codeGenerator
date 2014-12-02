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

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Metadata\DataObject\MetaDataInterface;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
interface GeneratorParserInterface
{
    /**
     * @param  GeneratorDataObject       $generator
     * @param  MetaDataInterface         $metadata
     * @throws \InvalidArgumentException
     * @return GeneratorDataObject
     */
    public function init(GeneratorDataObject $generator, MetaDataInterface $metadata);
}
