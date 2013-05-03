<?php

namespace CrudGenerator\Generators;

use CrudGenerator\Generators\DataObject;
use CrudGenerator\Generators\FileManager;
use CrudGenerator\Generators\Hydrator;
use CrudGenerator\Generators\View\ZendView;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCodeGenerator
{
    protected $filesystem     = null;
    protected $clientResponse = null;
    protected $hydrator       = null;
    protected $fileManager    = null;
    protected $skeletonDir    = null;

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
                    Hydrator $hydrator,
                    $skeletonDir)
    {
        $this->zendView    = $zendView;
        $this->output      = $output;
        $this->fileManager = $fileManager;
        $this->hydrator    = $hydrator;
        $this->skeletonDir = $skeletonDir;
    }

    /**
     * @param DataObject $dataObject
     * @throws \RuntimeException
     */
    abstract protected function doGenerate(DataObject $dataObject);

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

        $this->doGenerate($dataObject);
    }
    /**
     * @param DataObject $dataObject
     * @param string $pathTemplate
     * @param string $pathTo
     */
    protected function generateFile(DataObject $dataObject, $pathTemplate, $pathTo)
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
    protected function ifDirDoesNotExistCreate($dir)
    {
        if(!is_dir($dir)) {
            $this->fileManager->mkdir($dir);
            $this->output->writeln('--> Create dir ' . $dir);
        }
    }
}