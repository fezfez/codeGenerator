<?php
namespace CrudGenerator\Generators;

use CrudGenerator\DataObject;
use CrudGenerator\FileManager;
use CrudGenerator\View\View;
use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\Diff\DiffPHP;
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
     * @var DiffPHP
     */
    private $diffPHP             = null;

    /**
     * @param View $view
     * @param OutputInterface $output
     * @param FileManager $fileManager
     * @param DialogHelper $dialog
     * @param InputInterface $input
     * @param GeneriqueQuestions $generiqueQuestion
     * @param DiffPHP $diffPHP
     */
    public function __construct(
        View $view,
        OutputInterface $output,
        FileManager $fileManager,
        DialogHelper $dialog,
        InputInterface $input,
        GeneriqueQuestions $generiqueQuestion,
        DiffPHP $diffPHP
    ) {
        $this->view              = $view;
        $this->output            = $output;
        $this->fileManager       = $fileManager;
        $this->dialog            = $dialog;
        $this->input             = $input;
        $this->generiqueQuestion = $generiqueQuestion;
        $this->diffPHP           = $diffPHP;
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
        $metadata = $dataObject->getMetadata();
        if(empty($metadata)) {
            throw new \RuntimeException('Empty metadata');
        }

        $identifier = $metadata->getIdentifier();
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

        $results = $this->view->render(
            $this->skeletonDir,
            $pathTemplate,
            array_merge($datas, $suppDatas)
        );

        if(is_file($pathTo) && file_get_contents($pathTo) !== $results) {

            while(true) {
                $response = $this->dialog->select(
                    $this->output,
                    '<error>File "' . $pathTo . '" already exist, erase it with the new</error>',
                    array(
                        'postpone',
                        'show diff',
                        'erase',
                        'cancel'
                    )
                );
                if($response == '0') {
                    $this->fileManager->filePutsContent($pathTo . '.new', $results);
                    $diff = $this->diffPHP->diff($pathTo, $pathTo . '.new');
                    $this->fileManager->filePutsContent($pathTo . '.diff', $diff);
                    $this->output->writeln('--> Generate diff and new file ' . $pathTo . '.diff');
                    break;
                } elseif($response == '1') {
                    $this->fileManager->filePutsContent($pathTo . '.diff', $results);
                    $this->output->writeln(
                        '<info>' . $this->diffPHP->diff($pathTo, $pathTo . '.diff') . '</info>'
                    );
                    unlink($pathTo . '.diff');
                } elseif($response == '2') {
                    $this->fileManager->filePutsContent($pathTo, $results);
                    $this->output->writeln('--> Create ' . $pathTo);
                    break;
                } elseif($response == '3') {
                    break;
                }
            }
        } else {
            $this->fileManager->filePutsContent($pathTo, $results);
            $this->output->writeln('--> Create ' . $pathTo);
        }
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
