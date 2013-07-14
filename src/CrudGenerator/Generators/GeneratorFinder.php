<?php
namespace CrudGenerator\Generators;

use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;
use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;
use CrudGenerator\FileManager;

class GeneratorFinder
{
    /**
     * @var array
     */
    private $paths = array();
    /**
     * @var string
     */
    private $fileExtension = 'php';
    /**
     * @var FileManager
     */
    private $fileManager = null;

    /**
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }
    /**
     * @return array
     */
    public function getAllClasses()
    {
        $this->paths = array(
            __DIR__ . '/'
        );
        try {
            ZendFramework2Environnement::getDependence($this->fileManager);

            $previousDir = '.';

            while (!$this->fileManager->fileExists('config/autoload/global.php')) {
                $dir = dirname(getcwd());

                if ($previousDir === $dir) {
                    throw new \RuntimeException(
                        'Unable to locate "config/autoload/global.php": ' .
                        'is DoctrineModule in a subdir of your application skeleton?'
                    );
                }

                $previousDir = $dir;
                chdir($dir);
            }
            $config = $this->fileManager->includeFile('config/autoload/global.php');

            if(isset($config['crudGenerator'])) {
                foreach($config['crudGenerator']['path'] as $paths) {
                    $this->paths[] = $paths;
                }
            }
        } catch (EnvironnementResolverException $e) {
        }

        foreach ($this->paths as $path) {
            if (!is_dir($path)) {
                throw new \RuntimeException('invalid path ' . $path);
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
            if (is_object($parentClass)
                && in_array($sourceFile, $includedFiles)
                && $parentClass->name == 'CrudGenerator\Generators\BaseCodeGenerator') {
                $classes[] = $className;
            }
        }

        return $classes;
    }
}
