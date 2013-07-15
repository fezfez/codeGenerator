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
     * Generate the files
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

        if ($generateUnitTest == 'y') {
            $this->generateTestUnit($DTO, $entityName);
        }
    }

    /**
     * Generate unit for generated files
     * @param DataObject $DTO
     * @param string $entityName
     */
    private function generateTestUnit(DataObject $DTO, $entityName)
    {
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
