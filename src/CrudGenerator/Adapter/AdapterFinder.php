<?php
namespace CrudGenerator\Adapter;

use CrudGenerator\Adapter\AdapterCollection;
use CrudGenerator\Adapter\AdapterDataObject;
use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;

use ReflectionClass;
use RuntimeException;
use RegexIterator;
use RecursiveDirectoryIterator;
use RecursiveRegexIterator;
use RecursiveIteratorIterator;
use FilesystemIterator;

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
    public function getAllAdapters()
    {
        $this->paths = array(
            __DIR__ . '/../MetaData/'
        );

        foreach ($this->paths as $path) {
            if (!is_dir($path)) {
                throw new RuntimeException('invalid path ' . $path);
            }

            $iterator = new RegexIterator(
                new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::LEAVES_ONLY
                ),
                '/^.+' . preg_quote($this->fileExtension) . '$/i',
                RecursiveRegexIterator::GET_MATCH
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
            $reflectionClass = new ReflectionClass($className);
            $sourceFile = $reflectionClass->getFileName();
            $interfaces = $reflectionClass->getInterfaces();

            if (is_array($interfaces) && !empty($interfaces)
                && in_array($sourceFile, $includedFiles)
                && isset($interfaces['CrudGenerator\MetaData\MetaDataDAOInterface'])) {

                $adapterCollection->append(
                    $this->buildAdapterDataObject(
                        $className,
                        $reflectionClass,
                        $adapterDataObject
                    )
                );
            }
        }

        return $adapterCollection;
    }

    /**
     * @param string $adapterClassName
     * @param ReflectionClass $adapterReflectionClass
     * @param AdapterDataObject $adapterDataObject
     * @return AdapterDataObject
     */
    private function buildAdapterDataObject(
        $adapterClassName,
        ReflectionClass $adapterReflectionClass,
        AdapterDataObject $adapterDataObject)
    {
        $adapter = clone $adapterDataObject;

        $adapter->setName($adapterClassName);
        //$adapter->setDefinition($documentation);
        $configName = str_replace('MetaDataDAO', '', $adapterClassName) . 'Config';

        if(class_exists($configName)) {
            $adapter->setConfig(new $configName());
        }

        try {
            foreach($this->getFactoryEnvironnement($adapter) as $environementClass) {
                $environementClass::getDependence();
            }
        } catch (EnvironnementResolverException $e) {
            $adapter->setFalseDependencie($e->getMessage());
        }

        return $adapter;
    }

    /**
     * @param AdapterDataObject $adapter
     * @return array
     */
    private function getFactoryEnvironnement(AdapterDataObject $adapter)
    {
        $reflectionClass = new ReflectionClass($adapter->getFactory());

        $sDocComment = $reflectionClass->getDocComment();
        $sDocComment = trim(preg_replace("/(^[\\s]*\\/\\*\\*)
                        |(^[\\s]\\*\\/)
                        |(^[\\s]*\\*?\\s)
                        |(^[\\s]*)
                        |(^[\\t]*)/ixm", "", $sDocComment));

        $sDocComment = str_replace("\r", "", $sDocComment);
        $sDocComment = preg_replace("/([\\t])+/", "\t", $sDocComment);
        $aDocCommentLines = explode("\n", $sDocComment);
        $factoryEnv = array();
        $envString = '@CodeGenerator\Environnement';
        foreach($aDocCommentLines as $commentLine) {
            if(substr($commentLine, 0, strlen($envString)) === $envString) {
                $factoryEnv[] = 'CrudGenerator\EnvironnementResolver\\' . trim(str_replace($envString, '', $commentLine));
            }
        }

        return $factoryEnv;
    }
}
