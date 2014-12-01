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
            $previousDir = '.';

            $actualDir = getcwd();
            while ($fileManager->fileExists('config/application.config.php') === false) {
                $dir = dirname(getcwd());

                if ($previousDir === $dir) {
                    chdir($actualDir);
                    throw new EnvironnementResolverException(
                        'Unable to locate "config/application.config.php":
                        is CodeGenerator in a subdir of your application skeleton?'
                    );
                }

                $previousDir = $dir;
                chdir($dir);
            }

            try {
                self::$serviceManager = \Zend\Mvc\Application::init(
                    $fileManager->includeFile('config/application.config.php')
                )->getServiceManager();
            } catch (\Zend\ModuleManager\Exception\RuntimeException $e) {
                throw new EnvironnementResolverException($e->getMessage());
            }

            chdir($actualDir);
        }

        return self::$serviceManager;
    }
}
