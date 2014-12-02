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

use ReflectionClass;

/**
 * Allow to awake classes
 */
class ClassAwake
{
    /**
     * @var array
     */
    private static $included = array();

    /**
     * Awake classes by interface
     * @param  string[] $directories    Target directory
     * @param  string   $interfaceNames Interface name
     * @return array
     */
    public function wakeByInterfaces(array $directories, $interfaceNames)
    {
        $classCollection = $this->awake($directories);
        $classes         = array();

        foreach ($classCollection as $className) {
            $reflectionClass = new ReflectionClass($className);
            $interfaces      = $reflectionClass->getInterfaces();

            if (is_array($interfaces) === true && isset($interfaces[$interfaceNames]) === true) {
                $class           = str_replace('\\', '', strrchr($className, '\\'));
                $classes[$class] = $className;
            }
        }

        return $classes;
    }

    /**
     * Find classes on directory
     * @param  string[] $directories Target directory
     * @return array
     */
    private function awake(array $directories)
    {
        $includedFiles = array();

        self::$included = array_merge(self::$included, get_included_files());

        foreach ($directories as $directorie) {
            $iterator = new \RegexIterator(
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($directorie, \FilesystemIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                ),
                '/^.+' . preg_quote('.php') . '$/i',
                \RecursiveRegexIterator::GET_MATCH
            );

            foreach ($iterator as $file) {
                $sourceFile = realpath($file[0]);

                if (in_array($sourceFile, self::$included) === false) {
                    require_once $sourceFile;
                }

                self::$included[] = $sourceFile;
                $includedFiles[]  = $sourceFile;
            }
        }

        $classes  = array();
        $declared = get_declared_classes();

        foreach ($declared as $className) {
            $rc         = new \ReflectionClass($className);
            $sourceFile = $rc->getFileName();

            if (in_array($sourceFile, $includedFiles) === true) {
                $classes[] = $className;
            }
        }

        return $classes;
    }
}
