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
use CrudGenerator\Generators\ArchitectGenerator\Architect;
use CrudGenerator\Generators\ArchitectGenerator\MetadataToArray;
use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;
use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;
use CrudGenerator\Utils\FileManager;

use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\Generators\Strategies\StrategyInterface;
use CrudGenerator\Generators\GeneratorDependencies;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

/**
 * Generate DAO, DTO, Hydrator, Exception, unit test
 *
 * @author Stéphane Demonchaux
 */
class ArchitectGenerator extends BaseCodeGenerator
{
	/**
	 * @var MetadataToArray
	 */
	private $metadataToArray = null;
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
     * Base code generator
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @param GeneriqueQuestions $generiqueQuestion
     * @param StrategyInterface $strategy
     * @param MetadataToArray $metadataToArray
     */
    public function __construct(
        OutputInterface $output,
        DialogHelper $dialog,
        GeneriqueQuestions $generiqueQuestion,
        StrategyInterface $strategy,
        GeneratorDependencies $generatorDepencies,
    	MetadataToArray $metadataToArray
    ) {
    	parent::__construct($output, $dialog, $generiqueQuestion, $strategy, $generatorDepencies);
    	$this->metadataToArray = $metadataToArray;
    }
    /**
     * Generate the files
     * @param Architect $DTO
     * @throws \RuntimeException
     */
    public function doGenerate($DTO)
    {
        $this->output->writeln("<info>ArchitectGenerator</info>");
        $this->skeletonDir = __DIR__ . '/Skeleton';

        if (!$DTO->getDirectory()) {
            $DTO->setDirectory($this->generiqueQuestion->directoryQuestion($DTO));
        }
        if (!$DTO->getNamespace()) {
            $DTO->setNamespace($this->generiqueQuestion->namespaceQuestion());
        }
        if (null === $DTO->getModelName()) {
        	$DTO->setModelName(lcfirst($this->dialog->ask($this->output, 'Choose a model name')));
        }

        $DTO = $this->metadataToArray->ask($DTO);

        $basePath     = $DTO->getModule() . '/' . $DTO->getDirectory();
        $modelName    = $DTO->getModelName();
        $ucFirstModel = ucfirst($DTO->getModelName());

        $suppDatas = array(
            'modelName'              => $ucFirstModel,
            'ucfirstEntityName'       => $ucFirstModel,
            'hydratorName'            => $ucFirstModel . 'Hydrator',
            'dataObjectName'          => $ucFirstModel . 'DataObject',
            'collectionName'          => $ucFirstModel . 'Collection',
            'daoName'                 => $ucFirstModel . 'DAO',
            'exceptionName'           => 'No' . $ucFirstModel . 'Exception',
            'daoNamespace'            => $DTO->getNamespace() . '\DAO\\' . $ucFirstModel . 'DAO',
            'dtoNamespace'            => $DTO->getNamespace() . '\DataObject\\' . $ucFirstModel . 'DataObject',
            'hydratorNamespace'       => $DTO->getNamespace() . '\Hydrator\\' . $ucFirstModel . 'Hydrator',
            'dtoCollectionNamespace'  => $DTO->getNamespace() . '\DataObject\\' . $ucFirstModel . 'Collection',
            'exceptionNamespace'      => $DTO->getNamespace() . '\No' . $ucFirstModel . 'Exception',
        );

        $filesList = array(
            '/Exception.php.phtml'  => '/No' . $ucFirstModel . 'Exception.php',
            '/DAOFactory.php.phtml' => '/' . $ucFirstModel . 'DAOFactory.php',
            '/DAO.php.phtml' => '/DAO/' . $ucFirstModel . 'DAO.php',
            '/Hydrator.php.phtml' => '/Hydrator/' . $ucFirstModel . 'Hydrator.php',
            '/DataObject.php.phtml' => '/DataObject/' . $ucFirstModel . 'DataObject.php',
            '/DataObjectCollection.php.phtml' => '/DataObject/' . $ucFirstModel . 'Collection.php',
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
            $this->generateTestUnit($DTO, $ucFirstModel, $suppDatas);
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
        $fileManager = new FileManager();
        try {
            ZendFramework2Environnement::getDependence($fileManager);
            $unitTestDirectory = $DTO->getModule() . '/';
        } catch (EnvironnementResolverException $e) {
            $unitTestDirectory = '';
        }

        $unitTestDirectory   .= 'Tests/' . str_replace('\\', '/', $DTO->getNamespace()) . '/' . $entityName;
        $this->createFullPathDirIfNotExist($unitTestDirectory);

        try {
            ZendFramework2Environnement::getDependence($fileManager);

            $bootstrapPath = $DTO->getModule() . '/Tests/Bootstrap.php';
            $configPath    = $DTO->getModule() . '/Tests/TestConfig.php.dist';
            if (!$fileManager->isFile($bootstrapPath)) {
                $this->generateFile(
                    $DTO,
                     '/test/Bootstrap.php.zf2.phtml',
                    $bootstrapPath,
                    $suppDatas
                );
            }
            if (!$fileManager->isFile($bootstrapPath)) {
                $this->generateFile(
                    $DTO,
                    '/test/TestConfig.php.dist.zf2.phtml',
                    $configPath,
                    $suppDatas
                );
            }
        } catch (EnvironnementResolverException $e) {
        }

        $this->ifDirDoesNotExistCreate($unitTestDirectory . 'DAOFactory');
        $this->ifDirDoesNotExistCreate($unitTestDirectory . 'DAO');
        $this->ifDirDoesNotExistCreate($unitTestDirectory . 'Hydrator');

        $fixtureName = $entityName . 'Fixture';

        $suppDatas = array_merge(
            $suppDatas,
            array(
                'unitTestNamespace' => 'Tests\\' . $DTO->getNamespace() . '\\' . $entityName,
                'fixtureName'       => $fixtureName,
                'fixtureNamespace'  => 'Tests\\' . $DTO->getNamespace() . '\\' . $entityName . '\\' . $fixtureName
            )
        );

        $filesList = array(
            '/test/Fixture.php.phtml' => $unitTestDirectory . $entityName . 'Fixture.php',
            '/test/DAOFactory/getInstanceTest.php.phtml' => $unitTestDirectory . 'DAOFactory/GetInstanceTest.php',
            '/test/DAO/findTest.php.phtml' => $unitTestDirectory . 'DAO/FindTest.php',
            '/test/DAO/findAllTest.php.phtml' => $unitTestDirectory . 'DAO/FindAllTest.php',
            '/test/DAO/persistTest.php.phtml' => $unitTestDirectory . 'DAO/PersitTest.php',
            '/test/DAO/modifyTest.php.phtml' => $unitTestDirectory . 'DAO/ModifyTest.php',
            '/test/DAO/removeTest.php.phtml' => $unitTestDirectory . 'DAO/RemoveTest.php',
            '/test/Hydrator/arrayToPopoTest.php.phtml' =>  $unitTestDirectory . 'Hydrator/ArrayToPopoTest.php',
            '/test/Hydrator/popoToArrayTest.php.phtml' => $unitTestDirectory . 'Hydrator/PopoToArrayTest.php'
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
