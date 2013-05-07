<?php

namespace CrudGenerator;

class CrudFinder
{
    private $paths = array();
    private $fileExtension = 'php';

    public function getAllClasses()
    {
        $this->paths = array(
            __DIR__ . '/Generators/'
        );

        foreach ($this->paths as $path) {
            if ( ! is_dir($path)) {
                throw \Exception('invalid path ' . $path);
            }

            $iterator = new \RegexIterator(
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                ),
                '/^.+' . preg_quote($this->fileExtension) . '$/i',
                \RecursiveRegexIterator::GET_MATCH
            );

            foreach ($iterator as $file) {
                $sourceFile = realpath($file[0]);

                require_once $sourceFile;

                $includedFiles[] = $sourceFile;
            }
        }

        $declared = get_declared_classes();

        foreach ($declared as $className) {
            $rc = new \ReflectionClass($className);
            $sourceFile = $rc->getFileName();
            $parentClass = $rc->getParentClass();
            if (is_object($parentClass) && in_array($sourceFile, $includedFiles) && $parentClass->name == 'CrudGenerator\Generators\BaseCodeGenerator') {
                $classes[] = $className;
            }
        }

        return $classes;
    }
}