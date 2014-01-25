<?php
namespace CrudGenerator\FileConflict;

use CrudGenerator\Utils\FileManager;
use SebastianBergmann\Diff\Differ;

class FileConflictManagerWeb
{
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

    /**
     * @param string $filePath
     * @param string $results
     * @return string
     */
    public function handle($filePath, $results)
    {
    	return $this->diffPHP->diff($results, $this->fileManager->fileGetContent($filePath));
    }
}
