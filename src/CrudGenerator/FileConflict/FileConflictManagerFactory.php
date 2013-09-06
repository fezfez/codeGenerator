<?php
namespace CrudGenerator\FileConflict;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\DiffPHP;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

class FileConflictManagerFactory
{
    /**
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @return \CrudGenerator\FileConflict\FileConflictManager
     */
    public static function getInstance(OutputInterface $output, DialogHelper $dialog)
    {
        return new FileConflictManager(
            $output,
            $dialog,
            new FileManager(),
            new DiffPHP()
        );
    }
}