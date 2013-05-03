<?php

namespace CrudGenerator\Generators;

use CrudGenerator\DataObject;

class DoctrineCrudGenerator extends BaseCodeGenerator implements Generator
{
    /**
     * @param DataObject $dataObject
     * @throws \RuntimeException
     */
    protected function doGenerate(DataObject $dataObject)
    {
        $this->ifDirDoesNotExistCreate($dataObject->getViewPath());
        $this->ifDirDoesNotExistCreate($dataObject->getNamespacePath() . '/Form/');
        $this->ifDirDoesNotExistCreate($dataObject->getNamespacePath() . '/DAO/');
        $this->ifDirDoesNotExistCreate($dataObject->getNamespacePath() . '/Hydrator/');
        $this->ifDirDoesNotExistCreate($dataObject->getNamespacePath() . '/DataObject/');


        $this->generateFile($dataObject, '/crud/controller.php.phtml', $dataObject->getControllerPath() . $dataObject->getClassName() . 'Controller.php');
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
        }

        $this->generateFile($dataObject, '/form/FormType.php.phtml', $dataObject->getNamespacePath() . '/Form/' . $dataObject->getClassName() . 'Form.php');
        $this->generateFile($dataObject, '/architect/DAOFactory.php.phtml', $dataObject->getNamespacePath() . '/DAOFactory.php');
        $this->generateFile($dataObject, '/architect/DAO.php.phtml', $dataObject->getNamespacePath() . '/DAO/' . $dataObject->getClassName() . 'DAO.php');
        $this->generateFile($dataObject, '/architect/Exception.php.phtml', $dataObject->getNamespacePath() . '/No' . $dataObject->getClassName() . 'Exception.php');
        $this->generateFile($dataObject, '/architect/Hydrator.php.phtml', $dataObject->getNamespacePath() . '/Hydrator/' . $dataObject->getClassName() . 'Hydrator.php');
        $this->generateFile($dataObject, '/architect/DataObjectCollection.php.phtml', $dataObject->getNamespacePath() . '/DataObject/' . $dataObject->getClassName() . 'Collection.php');
        $this->generateFile($dataObject, '/architect/DataObject.php.phtml', $dataObject->getNamespacePath() . '/DataObject/' . $dataObject->getClassName() . 'DataObject.php');
        $this->generateFile($dataObject, '/architect/FormBuilderFactory.php.phtml', $dataObject->getNamespacePath() . '/' . $dataObject->getClassName() . 'FormBuilderFactory.php');
        $this->generateFile($dataObject, '/architect/FormBuilder.php.phtml', $dataObject->getNamespacePath() . '/Form/' . $dataObject->getClassName() . 'FormBuilder.php');
        //$this->_generateFile($dataObject, '/config/routes.ini.php.phtml', APPLICATION_PATH . '/configs/routes.ini');
    }
}
