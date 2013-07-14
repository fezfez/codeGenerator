<?php

namespace CrudGenerator\EnvironnementResolver;

use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;
use CrudGenerator\FileManager;

/**
 * ZendFramework2Environnement check if we are in zf2 env
 *
 * @author Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 */
class ZendFramework2Environnement
{
    /**
     * @var Zend\ServiceManager\ServiceManager Service manager
     */
    private static $serviceManager = null;

    /**
     * Check if we are in zf2 env
     *
     * @param FileManager $fileManager
     * @throws RuntimeException
     * @throws EnvironnementResolverException
     * @return \Zend\ServiceManager\ServiceManager
     */
    public static function getDependence(FileManager $fileManager)
    {
        if(null !== self::$serviceManager) {
            return self::$serviceManager;
        } else {
            $previousDir = '.';

            while (!$fileManager->fileExists('config/application.config.php')) {
                $dir = dirname(getcwd());

                if ($previousDir === $dir) {
                    throw new EnvironnementResolverException(
                        'Unable to locate "config/application.config.php": ' .
                        'is CrudGenerator in a subdir of your application skeleton?'
                    );
                }

                $previousDir = $dir;
                chdir($dir);
            }
            $application = \Zend\Mvc\Application::init($fileManager->includeFile('config/application.config.php'));

            self::$serviceManager = $application->getServiceManager();
            return self::$serviceManager;
        }
    }
}
