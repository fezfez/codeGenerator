<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\History;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Utils\Installer;

/**
 * HistoryManager instance
 *
 * @author Stéphane Demonchaux
 */
class HistoryManager
{
    /**
     * @var string History directory path
     */
    const HISTORY_PATH = 'History/';
    /**
     * @var string history file extension
     */
    const FILE_EXTENSION = '.history.yaml';
    /**
     * @var FileManager File manager
     */
    private $fileManager = null;
    /**
     * @var HistoryHydrator
     */
    private $historyHydrator = null;

    /**
     * @param FileManager     $fileManager
     * @param HistoryHydrator $historyHydrator
     */
    public function __construct(FileManager $fileManager, HistoryHydrator $historyHydrator)
    {
        $this->fileManager     = $fileManager;
        $this->historyHydrator = $historyHydrator;
    }

    /**
     * Create history
     * @param GeneratorDataObject $dataObject
     */
    public function create(GeneratorDataObject $dataObject)
    {
        $dto = $dataObject->getDto();

        if (null === $dto) {
            throw new \InvalidArgumentException('DTO cant be empty');
        }

        $metadata = $dto->getMetaData();

        if (null === $metadata) {
            throw new \InvalidArgumentException('Metadata cant be empty');
        }

        $fileName = $metadata->getName().self::FILE_EXTENSION;

        if ($this->fileManager->isFile(Installer::BASE_PATH.self::HISTORY_PATH.$fileName) === true) {
            $this->fileManager->unlink(Installer::BASE_PATH.self::HISTORY_PATH.$fileName);
        }

        $this->fileManager->filePutsContent(
            Installer::BASE_PATH.self::HISTORY_PATH.$fileName,
            $this->historyHydrator->dtoToJson($dataObject)
        );
    }

    /**
     * Retrieve all history
     * @throws EnvironnementResolverException
     * @return HistoryCollection
     */
    public function findAll()
    {
        if ($this->fileManager->isDir(Installer::BASE_PATH.self::HISTORY_PATH) === false) {
            throw new EnvironnementResolverException(
                sprintf(
                    'Unable to locate "%d"',
                    Installer::BASE_PATH.self::HISTORY_PATH
                )
            );
        }

        $historyCollection = new HistoryCollection();
        $searchPath        = Installer::BASE_PATH.self::HISTORY_PATH.'*'.self::FILE_EXTENSION;

        foreach ($this->fileManager->glob($searchPath) as $file) {
            $content = $this->fileManager->fileGetContent($file);

            try {
                $historyCollection->append($this->historyHydrator->jsonToDTO($content));
            } catch (InvalidHistoryException $e) {
                continue;
            }
        }

        return $historyCollection;
    }

    /**
     * @param  string                         $historyName
     * @throws HistoryNotFound
     * @return \CrudGenerator\History\History
     */
    public function find($historyName)
    {
        $filePath = Installer::BASE_PATH.self::HISTORY_PATH.$historyName.'.history.yaml';

        if ($this->fileManager->isFile($filePath) === false) {
            throw new HistoryNotFoundException(
                sprintf(
                    'History with name "%d" not found',
                    $historyName
                )
            );
        }

        return $this->historyHydrator->jsonToDTO(
            $this->fileManager->fileGetContent($filePath)
        );
    }
}
