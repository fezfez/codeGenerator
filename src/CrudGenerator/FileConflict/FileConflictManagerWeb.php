<?php
namespace CrudGenerator\FileConflict;

use CrudGenerator\Utils\FileManager;
use SebastianBergmann\Diff\Differ;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

class FileConflictManagerWeb
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
     * @var FileManager File Manager
     */
    private $fileManager = null;
    /**
     * @var Differ Diff php
     */
    private $diffPHP     = null;

    /**
     * @param FileManager $fileManager
     * @param DiffPHP $diffPHP
     */
    public function __construct(FileManager $fileManager, Differ $diffPHP)
    {
        $this->fileManager       = $fileManager;
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

    public function handle($filePath, $results)
    {
    	return $this->diffPHP->diff($results, $this->fileManager->fileGetContent($filePath));
    }
}
