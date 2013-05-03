<?php
namespace Generators\ArchitectGenerator;

use CrudGenerator\Generators\DataObject;
use CrudGenerator\Generators\FileManager;
use CrudGenerator\Generators\Hydrator;
use CrudGenerator\Generators\View\ZendView;
use CrudGenerator\Generators\GeneratorsInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ArchitectGenerator implements GeneratorsInterface
{
    private $filesystem     = null;
    private $clientResponse = null;
    private $hydrator       = null;
    private $fileManager    = null;
    private $skeletonDir    = null;

    /**
     * @param ZendView $zendView
     * @param OutputInterface $output
     * @param FileManager $fileManager
     * @param Hydrator $hydrator
     * @param string $skeletonDir
     */
    public function __construct(ZendView $zendView,
                                OutputInterface $output,
                                FileManager $fileManager,
                                Hydrator $hydrator)
    {
        $this->zendView    = $zendView;
        $this->output      = $output;
        $this->fileManager = $fileManager;
        $this->hydrator    = $hydrator;
    }

    /**
     * @param DataObject $dataObject
     * @throws \RuntimeException
     */
    public function generate(DataObject $dataObject)
    {
        var_dump($dataObject);exit;
        exit($dataObject->getViewPath());

        if (count($dataObject->getMetadata()->identifier) > 1) {
            throw new \RuntimeException('The CRUD generator does not support entity classes with multiple primary keys.');
        }

        if (!in_array('id', $dataObject->getMetadata()->identifier)) {
            throw new \RuntimeException('The CRUD generator expects the entity object has a primary key field named "id" with a getId() method.');
        }

        $this->ifDirDoesNotExistCreate($dataObject->getViewPath());
        $this->ifDirDoesNotExistCreate($dataObject->getNamespacePath() . '/DAO/');
        $this->ifDirDoesNotExistCreate($dataObject->getNamespacePath() . '/Hydrator/');
        $this->ifDirDoesNotExistCreate($dataObject->getNamespacePath() . '/DataObject/');

        $this->generateFile($dataObject, '/architect/DAOFactory.php.phtml', $dataObject->getNamespacePath() . '/DAOFactory.php');
        $this->generateFile($dataObject, '/architect/DAO.php.phtml', $dataObject->getNamespacePath() . '/DAO/' . $dataObject->getClassName() . 'DAO.php');
        $this->generateFile($dataObject, '/architect/Exception.php.phtml', $dataObject->getNamespacePath() . '/No' . $dataObject->getClassName() . 'Exception.php');
        $this->generateFile($dataObject, '/architect/Hydrator.php.phtml', $dataObject->getNamespacePath() . '/Hydrator/' . $dataObject->getClassName() . 'Hydrator.php');
        $this->generateFile($dataObject, '/architect/DataObjectCollection.php.phtml', $dataObject->getNamespacePath() . '/DataObject/' . $dataObject->getClassName() . 'Collection.php');
        $this->generateFile($dataObject, '/architect/DataObject.php.phtml', $dataObject->getNamespacePath() . '/DataObject/' . $dataObject->getClassName() . 'DataObject.php');
    }

    /**
     * @param DataObject $dataObject
     * @param string $pathTemplate
     * @param string $pathTo
     */
    private function generateFile(DataObject $dataObject, $pathTemplate, $pathTo)
    {
        $results = $this->zendView->render($this->_skeletonDir, $pathTemplate, array(
            'dir'        => $this->_skeletonDir,
            'dataObject' => $dataObject,
        ));

        $this->fileManager->filePutsContent($pathTo, $results);
        $this->output->writeln('--> Create ' . $pathTo);
    }

    /**
     * @param string $dir
     */
    private function ifDirDoesNotExistCreate($dir)
    {
        if(!is_dir($dir)) {
            $this->fileManager->mkdir($dir);
            $this->output->writeln('--> Create dir ' . $dir);
        }
    }
}
