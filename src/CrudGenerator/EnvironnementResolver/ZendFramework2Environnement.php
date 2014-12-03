<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\EnvironnementResolver;

use CrudGenerator\Utils\FileManager;
use Zend\Mvc\Application;
use Zend\ModuleManager\Exception\RuntimeException;

/**
 * ZendFramework2Environnement check if we are in zf2 env
 *
 * @author Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 */
class ZendFramework2Environnement
{
    /**
     * @var \Zend\ServiceManager\ServiceManager Service manager
     */
    private static $serviceManager = null;

    /**
     * Check if we are in zf2 env
     *
     * @param  FileManager                         $fileManager
     * @throws RuntimeException
     * @throws EnvironnementResolverException
     * @return \Zend\ServiceManager\ServiceManager
     */
    public static function getDependence(FileManager $fileManager)
    {
        if (null === self::$serviceManager) {
            $initialDirectory = getcwd();

            try {
                self::$serviceManager = Application::init(
                    $fileManager->includeFile(self::findConfigFile($initialDirectory, $fileManager))
                )->getServiceManager();
            } catch (RuntimeException $e) {
                throw new EnvironnementResolverException($e->getMessage());
            }

            chdir($initialDirectory);
        }

        return self::$serviceManager;
    }

    /**
     * @param string                          $initialDirectory
     * @param  FileManager                    $fileManager
     * @throws EnvironnementResolverException
     * @return string
     */
    private static function findConfigFile($initialDirectory, FileManager $fileManager)
    {
        $previousDir = '.';
        $fileToFind  = 'config/application.config.php';

        while ($fileManager->fileExists($fileToFind) === false) {
            $dir = dirname(getcwd());

            if ($previousDir === $dir) {
                chdir($initialDirectory);
                throw new EnvironnementResolverException(
                    'Unable to locate "config/application.config.php":
                        is CodeGenerator in a subdir of your application skeleton?'
                );
            }

            $previousDir = $dir;
            chdir($dir);
        }

        return $fileToFind;
    }
}
