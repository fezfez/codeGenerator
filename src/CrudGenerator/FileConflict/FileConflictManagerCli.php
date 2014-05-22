<?php
namespace CrudGenerator\FileConflict;

use CrudGenerator\Utils\FileManager;
use SebastianBergmann\Diff\Differ;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;
use CrudGenerator\Context\ContextInterface;

class FileConflictManagerCli
{
    /**
     * @var integer Post pone choise
     */
    const POSTPONE = 0;
    /**
     * @var integer Show diff choise
     */
    const SHOW_DIFF = 1;
    /**
     * @var integer Erase choise
     */
    const ERASE = 2;
    /**
     * @var integer Cancel choise
     */
    const CANCEL = 3;

    /**
     * @var ContextInterface Output
     */
    private $context      = null;
    /**
     * @var FileManager File Manager
     */
    private $fileManager = null;
    /**
     * @var Differ Diff php
     */
    private $diffPHP     = null;

    /**
     * @param ContextInterface $context
     * @param FileManager $fileManager
     * @param DiffPHP $diffPHP
     */
    public function __construct(
        ContextInterface $context,
        FileManager $fileManager,
        Differ $diffPHP
    ) {
        $this->context     = $context;
        $this->fileManager = $fileManager;
        $this->diffPHP     = $diffPHP;
    }

    /**
     * Test if there is a file conflict
     * @param string $filePath File location
     * @param string $newResult
     * @return boolean
     */
    public function test($filePath, $newResult)
    {
        return ($this->fileManager->isFile($filePath)
                        && $this->fileManager->fileGetContent($filePath) !== $newResult);
    }

    /**
     * Handle the file conflict
     * @param string $filePath File location
     * @param string $results
     */
    public function handle($filePath, $results)
    {
        while (true) {
            $response = $this->context->askCollection(
                'File "' . $filePath . '" already exist, erase it with the new',
                'conflict' . $filePath,
                array(
                    'postpone',
                    'show diff',
                    'erase',
                    'cancel'
                )
            );

            $response = intval($response);

            if ($response === self::SHOW_DIFF) {
                // write to output the diff
                $this->context->log(
                    '<info>' . $this->diffPHP->diff($results, $this->fileManager->fileGetContent($filePath)) . '</info>'
                );
            } else {
                break;
            }
        }

        if ($response === self::POSTPONE) {
            //Generate the diff file
            $this->fileManager->filePutsContent(
                $filePath . '.diff',
                $this->diffPHP->diff(
                    $results,
                    $this->fileManager->fileGetContent($filePath)
                )
            );
            $this->context->log('--> Generate diff and new file ' . $filePath . '.diff');
        } elseif ($response === self::ERASE) {
            $this->fileManager->filePutsContent($filePath, $results);
            $this->context->log('--> Create ' . $filePath);
        }
    }
}
