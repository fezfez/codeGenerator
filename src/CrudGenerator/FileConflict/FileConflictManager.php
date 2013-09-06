<?php
namespace CrudGenerator\FileConflict;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\DiffPHP;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

class FileConflictManager
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
     * @var OutputInterface Output
     */
    private $output      = null;
    /**
     * @var FileManager File Manager
     */
    private $fileManager = null;
    /**
     * @var DialogHelper Dialog
     */
    private $dialog      = null;
    /**
     * @var DiffPHP Diff php
     */
    private $diffPHP     = null;

    /**
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @param FileManager $fileManager
     * @param DiffPHP $diffPHP
     */
    public function __construct(
        OutputInterface $output,
        DialogHelper $dialog,
        FileManager $fileManager,
        DiffPHP $diffPHP
    ) {
        $this->output            = $output;
        $this->fileManager       = $fileManager;
        $this->dialog            = $dialog;
        $this->diffPHP           = $diffPHP;
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
            $response = $this->dialog->select(
                $this->output,
                '<error>File "' . $filePath . '" already exist, erase it with the new</error>',
                array(
                    'postpone',
                    'show diff',
                    'erase',
                    'cancel'
                )
            );

            $response = intval($response);

            if ($response === self::SHOW_DIFF) {
                // generate diff file
                $this->fileManager->filePutsContent($filePath . '.diff', $results);

                // write to output the diff
                $this->output->writeln(
                    '<info>' . $this->diffPHP->diff($filePath . '.diff', $filePath) . '</info>'
                );

                // delete the diff file
                $this->fileManager->unlink($filePath . '.diff');
            } else {
                break;
            }
        }

        if ($response === self::POSTPONE) {
            // Generate the new file
            $this->fileManager->filePutsContent($filePath . '.new', $results);

            //Generate the diff file
            $this->fileManager->filePutsContent(
                $filePath . '.diff',
                $this->diffPHP->diff(
                    $filePath . '.new',
                    $filePath
                )
            );
            $this->output->writeln('--> Generate diff and new file ' . $filePath . '.diff');
        } elseif ($response === self::ERASE) {
            $this->fileManager->filePutsContent($filePath, $results);
            $this->output->writeln('--> Create ' . $filePath);
        }
    }
}
