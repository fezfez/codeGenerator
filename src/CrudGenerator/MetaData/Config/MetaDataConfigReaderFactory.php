<?php
namespace CrudGenerator\MetaData\Config;

use CrudGenerator\MetaData\Config\MetaDataConfigReader;
use CrudGenerator\FileManager;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

/**
 * Create MetaDataConfigReader instance
 *
 * @author Stéphane Demonchaux
 */
class MetaDataConfigReaderFactory
{
    /**
     * Create MetaDataConfigReader instance
     *
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @return \CrudGenerator\MetaData\Config\MetaDataConfigReader
     */
    public static function getInstance(OutputInterface $output, DialogHelper $dialog)
    {
        $fileManager = new FileManager();
        return new MetaDataConfigReader($output, $dialog, $fileManager);
    }
}
