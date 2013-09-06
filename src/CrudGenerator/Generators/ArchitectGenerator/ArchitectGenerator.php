<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
namespace CrudGenerator\Generators\ArchitectGenerator;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\BaseCodeGenerator;
use CrudGenerator\Generators\ArchitectGenerator\Artchitect;

/**
 * Generate DAO, DTO, Hydrator, Exception, unit test
 *
 * @author Stéphane Demonchaux
 */
class ArchitectGenerator extends BaseCodeGenerator
{
    /**
     * @var string Skeleton directory
     */
    protected $skeletonDir = null;
    /**
     * @var string Generator definition
     */
    protected $definition  = 'Generate DAO, DTO, Hydrator, Exception, unit test';
    /**
     * @var string
     */
    protected $dto         = 'CrudGenerator\Generators\ArchitectGenerator\Architect';

    /**
     * Generate the files
     * @param DataObject $DTO
     * @throws \RuntimeException
     */
    protected function doGenerate($DTO)
    {
        $this->skeletonDir = __DIR__ . '/Skeleton';

        if (!$DTO->getDirectory()) {
            $DTO->setDirectory($this->generiqueQuestion->directoryQuestion($DTO));
        }
        if (!$DTO->getNamespace()) {
            $DTO->setNamespace($this->generiqueQuestion->namespaceQuestion());
        }

        $basePath          = $DTO->getModule() . '/' . $DTO->getDirectory();
        $entityName        = $DTO->getEntityName();
        $ucFirstEntityName = $DTO->getMetadata()->getName(true);

        $suppDatas = array(
            'entityName'        => $DTO->getMetadata()->getName(),
            'ucfirstEntityName' => $ucFirstEntityName,
            'hydratorName'      => $ucFirstEntityName . 'Hydrator',
            'dataObjectName'    => $ucFirstEntityName . 'DataObject',
            'collectionName'    => $ucFirstEntityName . 'Collection',
            'daoName'           => $ucFirstEntityName . 'DAO',
            'exceptionName'     => 'No' . $ucFirstEntityName . 'Exception',
            'daoNamespace'            => $DTO->getNamespace() . '\DAO\\' . $ucFirstEntityName . 'DAO',
            'dtoNamespace'            => $DTO->getNamespace() . '\DataObject\\' . $ucFirstEntityName . 'DataObject',
            'hydratorNamespace'       => $DTO->getNamespace() . '\Hydrator\\' . $ucFirstEntityName . 'Hydrator',
            'dtoCollectionNamespace'  => $DTO->getNamespace() . '\DataObject\\' . $ucFirstEntityName . 'Collection',
            'exceptionNamespace'      => $DTO->getNamespace() . '\No' . $ucFirstEntityName . 'Exception',
        );

        $filesList = array(
            '/Exception.php.phtml'  => '/No' . $entityName . 'Exception.php',
            '/DAOFactory.php.phtml' => '/' . $entityName . 'DAOFactory.php',
            '/DAO.php.phtml' => '/DAO/' . $entityName . 'DAO.php',
            '/Hydrator.php.phtml' => '/Hydrator/' . $entityName . 'Hydrator.php',
            '/DataObject.php.phtml' => '/DataObject/' . $entityName . 'DataObject.php',
            '/DataObjectCollection.php.phtml' => '/DataObject/' . $entityName . 'Collection.php',
        );

        $this->ifDirDoesNotExistCreate($basePath . '/DAO/');
        $this->ifDirDoesNotExistCreate($basePath . '/Hydrator/');
        $this->ifDirDoesNotExistCreate($basePath . '/DataObject/');

        foreach ($filesList as $template => $destination) {
            $this->generateFile(
                $DTO,
                $template,
                $basePath . $destination,
                $suppDatas
            );
        }

        $generateUnitTestDTO = $DTO->getGenerateUnitTest();

        if (null === $generateUnitTestDTO) {
            $generateUnitTest = $this->dialog->ask($this->output, 'Would you like to generate unitTest ?');
            $DTO->setGenerateUnitTest($generateUnitTest);
        } else {
            $generateUnitTest = $DTO->getGenerateUnitTest();
        }

        if ($generateUnitTest == 'y') {
            $this->generateTestUnit($DTO, $ucFirstEntityName, $suppDatas);
        }

        return $DTO;
    }

    /**
     * Generate unit for generated files
     * @param DataObject $DTO
     * @param string $entityName
     * @param array $suppDatas
     */
    private function generateTestUnit(DataObject $DTO, $entityName, array $suppDatas)
    {
        $unitTestDirectory = $DTO->getModule() . '/src/Tests/' . str_replace('\\', '/', $DTO->getNamespace()) . '/' . $entityName;

        $unitTestDirectories = explode('/', $unitTestDirectory);
        $allDir = '';
        foreach ($unitTestDirectories as $dir) {
            $allDir .= $dir . '/';
            $this->ifDirDoesNotExistCreate($allDir);
        }

        $this->ifDirDoesNotExistCreate($allDir . 'DAOFactory');
        $this->ifDirDoesNotExistCreate($allDir . 'DAO');
        $this->ifDirDoesNotExistCreate($allDir . 'Hydrator');

        $suppDatas = array_merge(
            $suppDatas,
            array(
                'unitTestNamespace' => $entityName . 'Test'
            )
        );

        $filesList = array(
            '/test/FixtureManager.php.phtml' => $unitTestDirectory . 'FixtureManager.php',
            '/test/Fixture.php.phtml' => $allDir . $entityName . 'Fixture.php',
            '/test/DAOFactory/getInstanceTest.php.phtml' => $allDir . 'DAOFactory/GetInstanceTest.php',
            '/test/DAO/findTest.php.phtml' => $allDir . 'DAO/FindTest.php',
            '/test/DAO/findAllTest.php.phtml' => $allDir . 'DAO/FindAllTest.php',
            '/test/DAO/persistTest.php.phtml' => $allDir . 'DAO/PersitTest.php',
            '/test/DAO/modifyTest.php.phtml' => $allDir . 'DAO/ModifyTest.php',
            '/test/DAO/removeTest.php.phtml' => $allDir . 'DAO/RemoveTest.php',
            '/test/Hydrator/arrayToPopoTest.php.phtml' =>  $allDir . 'Hydrator/ArrayToPopoTest.php',
            '/test/Hydrator/popoToArrayTest.php.phtml' => $allDir . 'Hydrator/PopoToArrayTest.php'
        );

        foreach ($filesList as $template => $destination) {
            $this->generateFile(
                $DTO,
                $template,
                $destination,
                $suppDatas
            );
        }
    }
}
