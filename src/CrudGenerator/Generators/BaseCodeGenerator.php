<?php
namespace CrudGenerator\Generators;

use CrudGenerator\DataObject;
use CrudGenerator\FileManager;
use CrudGenerator\View\View;
use CrudGenerator\Generators\GeneriqueQuestions;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;

abstract class BaseCodeGenerator
{
    /**
     * @var ZendView
     */
    protected $zendView          = null;
    /**
     * @var OutputInterface
     */
    protected $clientResponse    = null;
    /**
     * @var FileManager
     */
    protected $fileManager       = null;
    /**
     * @var DialogHelper
     */
    protected $dialog            = null;
    /**
     * @var InputInterface
     */
    protected $input             = null;
    /**
     * @var GeneriqueQuestions
     */
    protected $generiqueQuestion = null;

    /**
     * @param View $view
     * @param OutputInterface $output
     * @param FileManager $fileManager
     * @param DialogHelper $dialog
     * @param InputInterface $input
     * @param GeneriqueQuestions $generiqueQuestion
     */
    public function __construct(
        View $view,
        OutputInterface $output,
        FileManager $fileManager,
        DialogHelper $dialog,
        InputInterface $input,
        GeneriqueQuestions $generiqueQuestion
    ) {
        $this->view              = $view;
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

    /**
     * @param DataObject $dataObject
     * @throws \RuntimeException
     */
    public function generate(DataObject $dataObject)
    {
        $identifier = $dataObject->getMetadata()->getIdentifier();
        if (count($identifier) > 1) {
            throw new \RuntimeException('The generator does not support entity classes with multiple primary keys.');
        }

        if (!in_array('id', $identifier)) {
            throw new \RuntimeException(
                'The generator expects the entity object has a primary key field named "id" with a getId() method.'
            );
        }

        $this->doGenerate($dataObject);
    }

    /**
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param DataObject $dataObject
     * @param string $pathTemplate
     * @param string $pathTo
     * @param array $suppDatas
     */
    protected function generateFile(DataObject $dataObject, $pathTemplate, $pathTo, array $suppDatas = null)
    {
        if (null === $suppDatas) {
            $suppDatas = array();
        }
        $datas = array(
            'dir'        => $this->skeletonDir,
            'dataObject' => $dataObject,
        );

        $results = $this->view->render($this->skeletonDir, $pathTemplate, array_merge($datas, $suppDatas));

        $this->fileManager->filePutsContent($pathTo, $results);
        $this->output->writeln('--> Create ' . $pathTo);
    }

    /**
     * @param string $dir
     */
    protected function ifDirDoesNotExistCreate($dir)
    {
        if (!is_dir($dir)) {
            $this->fileManager->mkdir($dir);
            $this->output->writeln('--> Create dir ' . $dir);
        }
    }
}
