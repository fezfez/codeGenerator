<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\History;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Utils\FileManager;

/**
 * Create HistoryManager instance
 *
 * @author Stéphane Demonchaux
 */
class HistoryFactory
{
    /**
     * Create HistoryManager instance
     *
     * @return \CrudGenerator\History\HistoryManager
     */
    public static function getInstance(ContextInterface $context)
    {
        $fileManager     = new FileManager();
        $historyHydrator = HistoryHydratorFactory::getInstance($context);

        return new HistoryManager($fileManager, $historyHydrator);
    }
}
