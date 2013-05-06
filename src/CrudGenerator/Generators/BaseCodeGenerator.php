<?php

namespace CrudGenerator\Generators;

use CrudGenerator\DataObject;
use CrudGenerator\FileManager;
use CrudGenerator\Hydrator;
use CrudGenerator\View\ZendView;
use CrudGenerator\Generators\GeneriqueQuestions;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;

abstract class BaseCodeGenerator
{
    protected $zendView          = null;
    protected $clientResponse    = null;
    protected $fileManager       = null;
    protected $dialog            = null;
    protected $input             = null;
    protected $generiqueQuestion = null;

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
                    DialogHelper $dialog,
                    InputInterface $input,
                    GeneriqueQuestions $generiqueQuestion)
    {
        $this->zendView          = $zendView;
        $this->output            = $output;
        $this->fileManager       = $fileManager;
        $this->dialog            = $dialog;
        $this->input             = $input;
        $this->generiqueQuestion = $generiqueQuestion;

    }

    /**
     * @param DataObject $dataObject
     * @throws \RuntimeException
     */
    abstract protected function doGenerate(DataObject $dataObject);

    public function generate(DataObject $dataObject)
    {
        if (count($dataObject->getMetadata()->identifier) > 1) {
            throw new \RuntimeException('The generator does not support entity classes with multiple primary keys.');
        }

        if (!in_array('id', $dataObject->getMetadata()->identifier)) {
            throw new \RuntimeException('The generator expects the entity object has a primary key field named "id" with a getId() method.');
        }

        $this->doGenerate($dataObject);
    }

    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param DataObject $dataObject
     * @param string $pathTemplate
     * @param string $pathTo
     */
    protected function generateFile(DataObject $dataObject, $pathTemplate, $pathTo, array $suppDatas = null)
    {
        if(null === $suppDatas) {
            $suppDatas = array();
        }
        $datas = array(
            'dir'        => $this->skeletonDir,
            'dataObject' => $dataObject,
        );

        $results = $this->zendView->render($this->skeletonDir, $pathTemplate, array_merge($datas, $suppDatas));
        /*$this->fileManager->filePutsContent($pathTo . '.tmp', $results);

        echo $pathTo;
        $diff = new \CrudGenerator\Diff\DiffPHP($pathTo, $pathTo . '.tmp');exit;*/

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