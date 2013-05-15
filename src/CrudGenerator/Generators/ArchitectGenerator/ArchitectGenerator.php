<?php
namespace CrudGenerator\Generators\ArchitectGenerator;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\BaseCodeGenerator;

class ArchitectGenerator extends BaseCodeGenerator
{
    protected $skeletonDir = null;
    protected $definition  = 'Generate DAO, DTO, Hydrator, Exception, unit test';

    /**
     * @param DataObject $DTO
     * @throws \RuntimeException
     */
    protected function doGenerate(DataObject $DTO)
    {
        $this->skeletonDir = __DIR__ . '/Skeleton';

        $DTO->setDirectory($this->generiqueQuestion->directoryQuestion($DTO));
        $DTO->setNamespace($this->generiqueQuestion->namespaceQuestion());

        $basePath   = $DTO->getModule() . '/' . $DTO->getDirectory();
        $entityName = $DTO->getEntityName();

        $this->ifDirDoesNotExistCreate($basePath . '/DAO/');
        $this->ifDirDoesNotExistCreate($basePath . '/Hydrator/');
        $this->ifDirDoesNotExistCreate($basePath . '/DataObject/');

        $this->generateFile($DTO, '/Exception.php.phtml', $basePath . '/No' . $entityName . 'Exception.php');
        $this->generateFile($DTO, '/DAOFactory.php.phtml', $basePath . '/' . $entityName . 'DAOFactory.php');
        $this->generateFile($DTO, '/DAO.php.phtml', $basePath . '/DAO/' . $entityName . 'DAO.php');
        $this->generateFile($DTO, '/Hydrator.php.phtml', $basePath . '/Hydrator/' . $entityName . 'Hydrator.php');
        $this->generateFile($DTO, '/DataObject.php.phtml', $basePath . '/DataObject/' . $entityName . 'DataObject.php');
        $this->generateFile(
            $DTO,
            '/DataObjectCollection.php.phtml',
            $basePath . '/DataObject/' . $entityName . 'Collection.php'
        );

        $generateUnitTest = $this->dialog->ask($this->output, 'Would you like to generate unitTest ?');

        if ($generateUnitTest != 'y') {
            return false;
        }

        $moduleName = str_replace('/', '', strstr($DTO->getModule(), '/'));
        $unitTestDirectory = $DTO->getModule() . '/src/' . $moduleName . '/test/';
        $this->ifDirDoesNotExistCreate($unitTestDirectory);
        $this->ifDirDoesNotExistCreate($unitTestDirectory . $moduleName . 'Test');
        $unitTestDirectory = $unitTestDirectory. $moduleName . 'Test/';
        $suppdatas = array(
            'unitTestNamespace' => $moduleName . 'Test'
        );

        $this->generateFile(
            $DTO,
            '/test/FixtureManager.php.phtml',
            $unitTestDirectory . 'FixtureManager.php',
            $suppdatas
        );

        $modelDirectory = explode('\\', $this->generiqueQuestion->namespaceQuestion());
        $allDir = '';
        foreach ($modelDirectory as $dir) {
            $allDir .= $dir . '/';
            $this->ifDirDoesNotExistCreate($unitTestDirectory . $allDir);
        }

        $this->generateFile(
            $DTO,
            '/test/Fixture.php.phtml',
            $unitTestDirectory . $allDir . $entityName . 'Fixture.php',
            $suppdatas
        );

        $this->ifDirDoesNotExistCreate($unitTestDirectory . $allDir . '/DAOFactory');

        $this->generateFile(
            $DTO,
            '/test/DAOFactory/getInstanceTest.php.phtml',
            $unitTestDirectory . $allDir . 'DAOFactory/getInstanceTest.php',
            $suppdatas
        );

        $this->ifDirDoesNotExistCreate($unitTestDirectory . $allDir . '/DAO');

        $this->generateFile(
            $DTO,
            '/test/DAO/findTest.php.phtml',
            $unitTestDirectory . $allDir . 'DAO/findTest.php',
            $suppdatas
        );

        $this->generateFile(
            $DTO,
            '/test/DAO/findAllTest.php.phtml',
            $unitTestDirectory . $allDir . 'DAO/findAllTest.php',
            $suppdatas
        );

        $this->generateFile(
            $DTO,
            '/test/DAO/persistTest.php.phtml',
            $unitTestDirectory . $allDir . 'DAO/persitTest.php',
            $suppdatas
        );

        $this->generateFile(
            $DTO,
            '/test/DAO/modifyTest.php.phtml',
            $unitTestDirectory . $allDir . 'DAO/modifyTest.php',
            $suppdatas
        );

        $this->generateFile(
            $DTO,
            '/test/DAO/removeTest.php.phtml',
            $unitTestDirectory . $allDir . 'DAO/removeTest.php',
            $suppdatas
        );

        $this->ifDirDoesNotExistCreate($unitTestDirectory . $allDir . '/Hydrator');

        $this->generateFile(
            $DTO,
            '/test/Hydrator/arrayToPopoTest.php.phtml',
            $unitTestDirectory . $allDir . 'Hydrator/arrayToPopoTest.php',
            $suppdatas
        );

        $this->generateFile(
            $DTO,
            '/test/Hydrator/popoToArrayTest.php.phtml',
            $unitTestDirectory . $allDir . 'Hydrator/popoToArrayTest.php',
            $suppdatas
        );
    }
}
