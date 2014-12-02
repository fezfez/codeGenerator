<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\FileConflict\FileConflictManager;
use CrudGenerator\Generators\Strategies\StrategyInterface;
use CrudGenerator\History\HistoryManager;
use CrudGenerator\Utils\FileManager;

/**
 * @author Stéphane Demonchaux
 */
class Generator
{
    /**
     * @var StrategyInterface
     */
    private $strategy = null;
    /**
     * @var FileConflictManager
     */
    private $fileConflict = null;
    /**
     * @var FileManager
     */
    private $fileManager = null;
    /**
     * @var HistoryManager
     */
    private $historyManager = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param StrategyInterface   $strategy
     * @param FileConflictManager $fileConflict
     * @param FileManager         $fileManager
     * @param HistoryManager      $historyManager
     * @param ContextInterface    $context
     */
    public function __construct(
        StrategyInterface $strategy,
        FileConflictManager $fileConflict,
        FileManager $fileManager,
        HistoryManager $historyManager,
        ContextInterface $context
    ) {
        $this->strategy       = $strategy;
        $this->fileConflict   = $fileConflict;
        $this->fileManager    = $fileManager;
        $this->historyManager = $historyManager;
        $this->context        = $context;
    }

    /**
     * @param  GeneratorDataObject $generator
     * @throws \Exception
     * @return GeneratorDataObject
     */
    public function generate(GeneratorDataObject $generator)
    {
        foreach ($generator->getFiles() as $file) {
            $result = $this->strategy->generateFile(
                $generator->getTemplateVariables(),
                $file['skeletonPath'],
                $file['name'],
                $file['fileName']
            );

            if ($this->fileConflict->test($file['fileName'], $result) === true) {
                $this->fileConflict->handle($file['fileName'], $result);
            }
        }

        foreach ($generator->getFiles() as $file) {
            $result = $this->strategy->generateFile(
                $generator->getTemplateVariables(),
                $file['skeletonPath'],
                $file['name'],
                $file['fileName']
            );

            $this->fileManager->filePutsContent($file['fileName'], $result);
            $this->context->log('--> Create file ' . $file['fileName'], 'generationLog');
        }

        $this->historyManager->create($generator);

        return $generator;
    }

    /**
     * @param  GeneratorDataObject       $generator
     * @param  string                    $fileName
     * @throws \InvalidArgumentException
     */
    public function generateFile(GeneratorDataObject $generator, $fileName)
    {
        $fileToGenerate = null;
        foreach ($generator->getFiles() as $file) {
            if ($file['name'] === $fileName) {
                $fileToGenerate = $file;
            }
        }

        if (null === $fileToGenerate) {
            throw new \InvalidArgumentException('File does not exist');
        }

        $this->context->log(
            $this->strategy->generateFile(
                $generator->getTemplateVariables(),
                $fileToGenerate['skeletonPath'],
                $fileToGenerate['name'],
                $fileToGenerate['fileName']
            ),
            'previewfile'
        );
    }
}
