<?php
namespace CrudGenerator\FileConflict;

use CrudGenerator\Utils\FileManager;
use SebastianBergmann\Diff\Differ;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\WebContext;
use CrudGenerator\Context\CliContext;


class FileConflictManagerFactory
{
    /**
     * @param ContextInterface $context
     * @throws \InvalidArgumentException
     * @return \CrudGenerator\FileConflict\FileConflictManagerWeb|\CrudGenerator\FileConflict\FileConflictManagerCli
     */
    public static function getInstance(ContextInterface $context)
    {
        if ($context instanceof CliContext || $context instanceof WebContext) {
            return new FileConflictManagerCli(
                $context,
                new FileManager(),
                new Differ()
            );
        } else {
            throw new \InvalidArgumentException('Context "' . get_class($context) . '" not allowed');
        }
    }
}