<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\FileConflict;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Context\ContextInterface;
use SebastianBergmann\Diff\Differ;

class FileConflictManagerFactory
{
    /**
     * @param  ContextInterface          $context
     * @throws \InvalidArgumentException
     * @return FileConflictManager
     */
    public static function getInstance(ContextInterface $context)
    {
        return new FileConflictManager(
            $context,
            new FileManager(),
            new Differ()
        );
    }
}
