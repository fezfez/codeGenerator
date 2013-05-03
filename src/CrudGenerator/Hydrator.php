<?php
namespace CrudGenerator\Generators;

use CrudGenerator\Generators\DataObject;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class Hydrator
{
    /**
     * @param string $modulesName
     * @param string $entity
     * @param ClassMetadataInfo $metadata
     * @param bool $needWriteActions
     * @param string $namespace
     * @return CrudGenerator\Generator\DataObject
     */
    public function scaleToPopo($modulesName, $entityFullNamespace, ClassMetadataInfo $metadata, $needWriteActions, $namespace)
    {
        $actions = $needWriteActions ? array('index', 'show', 'new', 'edit', 'delete') : array('index', 'show');
        $parts           = explode('\\', $entityFullNamespace);
        $entityClassName = ucfirst(array_pop($parts));
        $entityNamespace = implode('\\', $parts);
        $route_prefix    = strtolower($entityClassName);

        // Path list, for generator ONLY
        $moduleBase      = APPLICATION_PATH . 'modules/' . $modulesName . '/';
        $controllerPath  = $moduleBase . 'controllers/';
        $viewPath        = $moduleBase . 'views/scripts/' . strtolower($entityClassName) . '/';
        $namespacePath   = APPLICATION_PATH . 'models/' . str_replace('\\', '/', $namespace);

        $dataObject = new DataObject();
        $dataObject->setActions($actions)
                   ->setClassName($entityClassName)
                   ->setEntity($entityFullNamespace)
                   ->setMetadata($metadata)
                   ->setModulesName($modulesName)
                   ->setNamespace($namespace)
                   ->setParts($parts)
                   ->setRoutesPrefixe($route_prefix)
                   ->setEntityNamespace($entityNamespace)
                   ->setViewPath($viewPath)
                   ->setControllerPath($controllerPath)
                   ->setNamespacePath($namespacePath);

        return $dataObject;
    }
}