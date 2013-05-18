<?php
namespace CrudGenerator\Adapter;

class AdapterFinder
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
     * @return AdapterCollection
     */
    public function getAllClasses()
    {
        $this->paths = array(
            __DIR__ . '/../MetaData/'
        );

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

        $adapterCollection = new AdapterCollection();
        $adapterDataObject = new AdapterDataObject();
        foreach ($declared as $className) {
            $rc = new \ReflectionClass($className);
            $sourceFile = $rc->getFileName();
            $interfaces = $rc->getInterfaces();

            if (is_array($interfaces) && !empty($interfaces)
                && in_array($sourceFile, $includedFiles)
                && isset($interfaces['CrudGenerator\MetaData\MetaDataDAOInterface'])) {
                $adapter = clone $adapterDataObject;

                $rc2 = new \ReflectionClass($className . 'Factory');

                $definition = $rc->getDocComment();
                $parameters = $rc2->getMethod('getInstance')->getParameters();

                $adapter->setName($className);
                $adapter->setDefinition($definition);
                foreach ($parameters as $param) {
                    try {
                        class_exists($param->getClass()->name);
                    } catch (\ReflectionException $e) {
                        $adapter->addFalseDependencie($e->getMessage());
                    }
                }
                $adapterCollection->append($adapter);
            }
        }

        return $adapterCollection;
    }
}
