<?php
namespace CrudGenerator\Generators\CrudGenerator;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\BaseCodeGenerator;

class CrudGenerator extends BaseCodeGenerator
{
    protected $skeletonDir    = null;
    protected $definition     = 'Generate CRUD based on ArchitectGenerator utilisation';

    /**
     * @param DataObject $dataObject
     * @throws \RuntimeException
     */
    protected function doGenerate(DataObject $dataObject)
    {
        $this->skeletonDir = __DIR__ . '/Skeleton';

        $writeAction = $this->dialog->askConfirmation(
            $this->output,
            '<question>Do you want to generate the "write" actions ?</question> '
        );

        $this->generateFile(
            $dataObject,
            '/crud/controller.php.phtml',
            $dataObject->getControllerPath() . $dataObject->getClassName() . 'Controller.php'
        );
        $this->generateFile($dataObject, '/crud/views/index.phtml.phtml', $dataObject->getViewPath() . 'index.phtml');

        if (in_array('show', $dataObject->getActions())) {
            $this->generateFile($dataObject, '/crud/views/show.phtml.phtml', $dataObject->getViewPath() . 'show.phtml');
        }

        if (in_array('new', $dataObject->getActions())) {
            $this->generateFile($dataObject, '/crud/views/new.phtml.phtml', $dataObject->getViewPath() . 'new.phtml');
        }

        if (in_array('edit', $dataObject->getActions())) {
            $this->generateFile($dataObject, '/crud/views/edit.phtml.phtml', $dataObject->getViewPath() . 'edit.phtml');
            $this->generateFile(
                $dataObject,
                '/crud/views/edit-js.phtml.phtml',
                $dataObject->getViewPath() . 'edit-js.phtml'
            );
        }
    }
}
