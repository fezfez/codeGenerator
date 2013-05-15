<?php
namespace CrudGenerator\Generators\CrudGenerator;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\BaseCodeGenerator;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

        $this->ifDirDoesNotExistCreate($dataObject->getModule() . '/' . $dataObject->getDirectory() . '/Form/');

        $formPath = $dataObject->getModule() . '/' . $dataObject->getNamespacePath() . '/Form/';

        $this->generateFile(
            $dataObject,
            '/form/FormType.php.phtml',
            $formPath . $dataObject->getEntityName() . 'Form.php'
        );
        $this->generateFile(
            $dataObject,
            '/architect/FormBuilder.php.phtml',
            $formPath . $dataObject->getEntityName() . 'FormBuilder.php'
        );
    }
}
