<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Utils;

use CrudGenerator\History\HistoryManager;
use CrudGenerator\Metadata\Config\MetaDataConfigDAO;
use CrudGenerator\Metadata\Sources\Xml\XmlMetaDataDAOFactory;

/**
 * Installer
 *
 * @author Stéphane Demonchaux
 */
class Installer
{
    const BASE_PATH = 'data/crudGenerator/';
    /**
     * @var string
     */
    const CACHE_PATH = 'cache/';

    const CACHE = 'Cache';
    const HISTORY = 'History';
    const CONFIG = 'Config';
    const TMP = 'tmp';

    /**
     * @return array
     */
    public static function getDirectories()
    {
        $directoriestoCreate = array(
            self::CACHE   => getcwd().DIRECTORY_SEPARATOR.self::BASE_PATH.self::CACHE_PATH,
            self::HISTORY => getcwd().DIRECTORY_SEPARATOR.self::BASE_PATH.HistoryManager::HISTORY_PATH,
            self::CONFIG  => getcwd().DIRECTORY_SEPARATOR.self::BASE_PATH.MetaDataConfigDAO::SOURCE_PATH,
            self::TMP     => getcwd().DIRECTORY_SEPARATOR.self::BASE_PATH.XmlMetaDataDAOFactory::TMP_PATH,
        );

        return $directoriestoCreate;
    }

    /**
     * @param  string $name
     * @return string
     */
    public static function getDirectory($name)
    {
        $directories = self::getDirectories();

        return $directories[$name];
    }

    /**
     * @param  FileManager       $fileManager
     * @throws \RuntimeException
     */
    public static function install(FileManager $fileManager)
    {
        $directoriestoCreate = self::getDirectories();

        foreach ($directoriestoCreate as $name => $path) {
            $fileManager->ifDirDoesNotExistCreate($path, true);

            if ($fileManager->isWritable($path) === false) {
                throw new \RuntimeException(sprintf('%s directory "%s" is not writable', $name, $path));
            }
        }
    }
}
