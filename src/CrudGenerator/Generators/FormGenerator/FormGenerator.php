<?php
namespace CrudGenerator\Generators\FormGenerator;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\BaseCodeGenerator;

class FormGenerator extends BaseCodeGenerator
{
    protected $skeletonDir    = null;
    protected $definition     = 'Generate forms';

    /**
     * @param DataObject $dataObject
     * @throws \RuntimeException
     */
    protected function doGenerate(DataObject $dataObject)
    {
        $this->skeletonDir = __DIR__ . '/Skeleton';

        $dataObject->setDirectory($this->generiqueQuestion->directoryQuestion($dataObject));
        $dataObject->setNamespace($this->generiqueQuestion->namespaceQuestion());
        $basePath = $dataObject->getModule() . '/' . $dataObject->getDirectory() . '/';
        $formPath = $basePath . 'Form/';

        $this->ifDirDoesNotExistCreate($formPath);

        $this->generateFile(
            $dataObject,
            '/form/FormFactory.phtml',
            $basePath . $dataObject->getEntityName() . 'FormFactory.php'
        );
        $this->generateFile(
            $dataObject,
            '/form/AbstractForm.phtml',
            $formPath . 'Abstract' . $dataObject->getEntityName() . 'Form.php'
        );
        $this->generateFile(
            $dataObject,
            '/form/Form.phtml',
            $formPath . $dataObject->getEntityName() . 'Form.php'
        );
        $this->generateFile(
            $dataObject,
            '/form/FormFilter.phtml',
            $formPath . $dataObject->getEntityName() . 'FormFilter.php'
        );
        $this->generateFile(
            $dataObject,
            '/form/Print.phtml',
            $formPath . 'FormPrint.phtml'
        );
    }
}
