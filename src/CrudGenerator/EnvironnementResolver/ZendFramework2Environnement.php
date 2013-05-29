<?php

namespace CrudGenerator\EnvironnementResolver;

use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;

class ZendFramework2Environnement
{
    /**
     * @var Zend\ServiceManager\ServiceManager
     */
    private static $serviceManager = null;

    /**
     * @throws RuntimeException
     * @throws EnvironnementResolverException
     * @return \Zend\ServiceManager\ServiceManager
     */
    public static function getDependence()
    {
        if(null !== self::$serviceManager) {
            return self::$serviceManager;
        } elseif (class_exists('Zend\Mvc\Application')) {
            $previousDir = '.';

            while (!file_exists('config/application.config.php')) {
                $dir = dirname(getcwd());

                if ($previousDir === $dir) {
                    throw new RuntimeException(
                        'Unable to locate "config/application.config.php": ' .
                        'is DoctrineModule in a subdir of your application skeleton?'
                    );
                }

                $previousDir = $dir;
                chdir($dir);
            }

            $application = \Zend\Mvc\Application::init(include 'config/application.config.php');
            self::$serviceManager = $application->getServiceManager();

            return self::$serviceManager;
        } else {
            throw new EnvironnementResolverException("Not in zf2 env");
        }
    }
}
