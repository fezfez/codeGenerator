<?php

namespace CrudGenerator\Generators;

use CrudGenerator\DataObject;

class DoctrineCrudGenerator extends BaseCodeGenerator
{
    protected $skeletonDir    = null;
    /**
     * @param DataObject $dataObject
     * @throws \RuntimeException
     */
    protected function doGenerate(DataObject $dataObject)
    {
        $this->skeletonDir = __DIR__ . '/ArchitectGenerator/Skeleton';

        //$this->ifDirDoesNotExistCreate($dataObject->getModule() . '');
        $this->ifDirDoesNotExistCreate($dataObject->getModule() . '/' . $dataObject->getDirectory() . '/Form/');
        $this->ifDirDoesNotExistCreate($dataObject->getModule() . '/' . $dataObject->getDirectory() . '/DAO/');
        $this->ifDirDoesNotExistCreate($dataObject->getModule() . '/' . $dataObject->getDirectory() . '/Hydrator/');
        $this->ifDirDoesNotExistCreate($dataObject->getModule() . '/' . $dataObject->getDirectory() . '/DataObject/');


        /*$this->generateFile($dataObject, '/crud/controller.php.phtml', $dataObject->getControllerPath() . $dataObject->getClassName() . 'Controller.php');
        $this->generateFile($dataObject, '/crud/views/index.phtml.phtml', $dataObject->getViewPath() . 'index.phtml');

        if (in_array('show', $dataObject->getActions())) {
            $this->generateFile($dataObject, '/crud/views/show.phtml.phtml', $dataObject->getViewPath() . 'show.phtml');
        }

        if (in_array('new', $dataObject->getActions())) {
            $this->generateFile($dataObject, '/crud/views/new.phtml.phtml', $dataObject->getViewPath() . 'new.phtml');
        }

        if (in_array('edit', $dataObject->getActions())) {
            $this->generateFile($dataObject, '/crud/views/edit.phtml.phtml', $dataObject->getViewPath() . 'edit.phtml');
            $this->generateFile($dataObject, '/crud/views/edit-js.phtml.phtml', $dataObject->getViewPath() . 'edit-js.phtml');
        }*/

        //$this->generateFile($dataObject, '/form/FormType.php.phtml', $dataObject->getModule() . '/' . $dataObject->getNamespacePath() . '/Form/' . $dataObject->getEntityName() . 'Form.php');
        $this->generateFile($dataObject, '/DAOFactory.php.phtml', $dataObject->getModule() . '/' . $dataObject->getDirectory() . '/' . $dataObject->getEntityName() . 'DAOFactory.php');
        $this->generateFile($dataObject, '/DAO.php.phtml', $dataObject->getModule() . '/' . $dataObject->getDirectory() . '/DAO/' . $dataObject->getEntityName() . 'DAO.php');
        $this->generateFile($dataObject, '/Exception.php.phtml', $dataObject->getModule() . '/' . $dataObject->getDirectory() . '/No' . $dataObject->getEntityName() . 'Exception.php');
        $this->generateFile($dataObject, '/Hydrator.php.phtml', $dataObject->getModule() . '/' . $dataObject->getDirectory() . '/Hydrator/' . $dataObject->getEntityName() . 'Hydrator.php');
        $this->generateFile($dataObject, '/DataObjectCollection.php.phtml', $dataObject->getModule() . '/' . $dataObject->getDirectory() . '/DataObject/' . $dataObject->getEntityName() . 'Collection.php');
        $this->generateFile($dataObject, '/DataObject.php.phtml', $dataObject->getModule() . '/' . $dataObject->getDirectory() . '/DataObject/' . $dataObject->getEntityName() . 'DataObject.php');
        //$this->generateFile($dataObject, '/architect/FormBuilderFactory.php.phtml', $dataObject->getModule() . '/' . $dataObject->getNamespacePath() . '/' . $dataObject->getEntityName() . 'FormBuilderFactory.php');
        //$this->generateFile($dataObject, '/architect/FormBuilder.php.phtml', $dataObject->getModule() . '/' . $dataObject->getNamespacePath() . '/Form/' . $dataObject->getEntityName() . 'FormBuilder.php');
        //$this->_generateFile($dataObject, '/config/routes.ini.php.phtml', APPLICATION_PATH . '/configs/routes.ini');
    }
}
