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
    protected $definition  = 'Generate DAO, DTO, Hydrator, Exception, unit test';

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

        $generateUnitTest = $this->dialog->ask($this->output, 'Would you like to generate unitTest ?');

        if($generateUnitTest != 'y') {
            return false;
        }

        $moduleName = str_replace('/', '', strstr($dataObject->getModule(), '/'));
        $unitTestDirectory = $dataObject->getModule() . '/src/' . $moduleName . '/test/';
        $this->ifDirDoesNotExistCreate($unitTestDirectory);
        $this->ifDirDoesNotExistCreate($unitTestDirectory . $moduleName . 'Test');
        $unitTestDirectory = $unitTestDirectory. $moduleName . 'Test/';
        $suppdatas = array(
            'unitTestNamespace' => $moduleName . 'Test'
        );

        $this->generateFile(
            $dataObject, '/test/FixtureManager.php.phtml',
            $unitTestDirectory . 'FixtureManager.php',
            $suppdatas
        );

        $modelDirectory = explode('\\', $this->generiqueQuestion->namespaceQuestion());
        $allDir = '';
        foreach($modelDirectory as $dir) {
            $allDir .= '/' . $dir;
            $this->ifDirDoesNotExistCreate($unitTestDirectory . $allDir);
        }

        $this->ifDirDoesNotExistCreate($unitTestDirectory . $allDir . '/DAOFactory');

        $this->generateFile(
            $dataObject, '/test/DAOFactory/getInstanceTest.php.phtml',
            $unitTestDirectory . $allDir . '/DAOFactory/getInstanceTest.php',
            $suppdatas
        );

        $this->ifDirDoesNotExistCreate($unitTestDirectory . $allDir . '/DAO');

        $this->generateFile(
            $dataObject, '/test/DAO/findTest.php.phtml',
            $unitTestDirectory . $allDir . '/DAO/findTest.php',
            $suppdatas
        );

        $this->generateFile(
            $dataObject, '/test/Fixture.php.phtml',
            $unitTestDirectory . $allDir . '/' . $dataObject->getEntityName() . 'Fixture.php',
            $suppdatas
        );
    }
}