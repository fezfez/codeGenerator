<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
namespace CrudGenerator\History;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\History\HistoryCollection;
use CrudGenerator\History\History;
use CrudGenerator\History\HistoryHydrator;
use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;
use CrudGenerator\Generators\GeneratorDataObject;

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
    const HISTORY_PATH = 'data/crudGenerator/History/';
    /**
     * @var FileManager File manager
     */
    private $fileManager = null;
    /**
     * @var HistoryHydrator
     */
    private $historyHydrator = null;

    /**
     * @param FileManager $fileManager
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
        if ($this->fileManager->isDir(self::HISTORY_PATH) === false) {
            $this->fileManager->mkdir(self::HISTORY_PATH);
        }

        $dto = $dataObject->getDTO();

        if (null === $dto) {
            throw new \InvalidArgumentException('DTO cant be empty');
        }

        $metadata = $dto->getMetaData();

        if (null === $metadata) {
            throw new \InvalidArgumentException('Metadata cant be empty');
        }

        $fileName = $metadata->getName(). '.history.yaml';

        if ($this->fileManager->isFile(self::HISTORY_PATH . $fileName) === true) {
            $this->fileManager->unlink(self::HISTORY_PATH . $fileName);
        }

        $this->fileManager->filePutsContent(
            self::HISTORY_PATH . $fileName,
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
        if ($this->fileManager->isDir(self::HISTORY_PATH) === false) {
            throw new EnvironnementResolverException(
                sprintf(
                    'Unable to locate "%d"',
                    self::HISTORY_PATH
                )
            );
        }

        $historyCollection = new HistoryCollection();

        foreach ($this->fileManager->glob(self::HISTORY_PATH . '*.history.yaml') as $file) {
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
     * @param string $historyName
     * @throws HistoryNotFound
     * @return \CrudGenerator\History\History
     */
    public function find($historyName)
    {
        $filePath = self::HISTORY_PATH . $historyName . '.history.yaml';

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
