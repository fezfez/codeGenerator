<?php

namespace CrudGenerator\EnvironnementResolver;

use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;

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

            if(file_exists('tests/CrudGenerator/Tests/ZF2/config/application.config.php')) {
                $application = \Zend\Mvc\Application::init(include 'tests/CrudGenerator/Tests/ZF2/config/application.config.php');
            } else {
                while (!file_exists('config/application.config.php')) {
                    $dir = dirname(getcwd());

                    echo $dir . "\n";

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
            }

            self::$serviceManager = $application->getServiceManager();

            return self::$serviceManager;
        } else {
            throw new EnvironnementResolverException("Not in zf2 env");
        }
    }
}
