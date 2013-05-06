<?php
namespace CrudGenerator\Generators\ArchitectGenerator;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\BaseCodeGenerator;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ArchitectGenerator extends BaseCodeGenerator
{
    protected $skeletonDir = null;
    protected $definition  = 'Generate DAO, DTO, Hydrator, Exception';

    /**
     * @param DataObject $dataObject
     * @throws \RuntimeException
     */
    protected function doGenerate(DataObject $dataObject)
    {
        $this->skeletonDir = __DIR__ . '/Skeleton';

        $dataObject->setDirectory($this->generiqueQuestion->directoryQuestion($dataObject));
        $dataObject->setNamespace($this->generiqueQuestion->namespaceQuestion());

        $this->ifDirDoesNotExistCreate($dataObject->getModule() . '/' . $dataObject->getDirectory() . '/DAO/');
        $this->ifDirDoesNotExistCreate($dataObject->getModule() . '/' . $dataObject->getDirectory() . '/Hydrator/');
        $this->ifDirDoesNotExistCreate($dataObject->getModule() . '/' . $dataObject->getDirectory() . '/DataObject/');

        $this->generateFile($dataObject, '/DAOFactory.php.phtml', $dataObject->getModule() . '/' . $dataObject->getDirectory() . '/' . $dataObject->getEntityName() . 'DAOFactory.php');
        $this->generateFile($dataObject, '/DAO.php.phtml', $dataObject->getModule() . '/' . $dataObject->getDirectory() . '/DAO/' . $dataObject->getEntityName() . 'DAO.php');
        $this->generateFile($dataObject, '/Exception.php.phtml', $dataObject->getModule() . '/' . $dataObject->getDirectory() . '/No' . $dataObject->getEntityName() . 'Exception.php');
        $this->generateFile($dataObject, '/Hydrator.php.phtml', $dataObject->getModule() . '/' . $dataObject->getDirectory() . '/Hydrator/' . $dataObject->getEntityName() . 'Hydrator.php');
        $this->generateFile($dataObject, '/DataObjectCollection.php.phtml', $dataObject->getModule() . '/' . $dataObject->getDirectory() . '/DataObject/' . $dataObject->getEntityName() . 'Collection.php');
        $this->generateFile($dataObject, '/DataObject.php.phtml', $dataObject->getModule() . '/' . $dataObject->getDirectory() . '/DataObject/' . $dataObject->getEntityName() . 'DataObject.php');
    }
}