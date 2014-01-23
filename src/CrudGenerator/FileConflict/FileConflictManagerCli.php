<?php
namespace CrudGenerator\FileConflict;

use CrudGenerator\Utils\FileManager;
use SebastianBergmann\Diff\Differ;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

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
     * @var Differ Diff php
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
        Differ $diffPHP
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
                // write to output the diff
                $this->output->writeln(
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
            $this->output->writeln('--> Generate diff and new file ' . $filePath . '.diff');
        } elseif ($response === self::ERASE) {
            $this->fileManager->filePutsContent($filePath, $results);
            $this->output->writeln('--> Create ' . $filePath);
        }
    }
}
