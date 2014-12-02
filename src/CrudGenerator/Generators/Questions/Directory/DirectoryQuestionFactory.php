<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Questions\Directory;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Utils\FileManager;

class DirectoryQuestionFactory
{
    /**
     * @param  ContextInterface                                                $context
     * @return \CrudGenerator\Generators\Questions\Directory\DirectoryQuestion
     */
    public static function getInstance(ContextInterface $context)
    {
        return new DirectoryQuestion(new FileManager(), $context);
    }
}
