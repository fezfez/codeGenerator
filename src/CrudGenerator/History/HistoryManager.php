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

use CrudGenerator\DataObject;
use CrudGenerator\FileManager;
use CrudGenerator\History\HistoryCollection;
use CrudGenerator\History\History;
use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;

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
    const HISTORY_PATH = 'data/crudGeneratorHistory/';
    /**
     * @var FileManager File manager
     */
    private $fileManager = null;

    /**
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * Create history
     * @param DataObject $dataObject
     */
    public function create(DataObject $dataObject)
    {
        if (!$this->fileManager->isDir(self::HISTORY_PATH)) {
            $this->fileManager->mkdir(self::HISTORY_PATH);
        }

        $fileName = md5($dataObject->getEntity());

        if ($this->fileManager->isFile(self::HISTORY_PATH . $fileName)) {
            $this->fileManager->unlink(self::HISTORY_PATH . $fileName);
        }

        $history = new History();
        $history->setName($dataObject->getEntity())
                ->setDataObject($dataObject);

        $this->fileManager->filePutsContent(
            self::HISTORY_PATH . $fileName . '.history',
            serialize($history)
        );
    }

    /**
     * Retrieve all history
     */
    public function findAll()
    {
        $previousDir = '.';
        while (!$this->fileManager->isDir('vendor/fezfez')) {
            $dir = dirname(getcwd());

            if ($previousDir === $dir) {
                throw new EnvironnementResolverException(
                    'Unable to locate "vendor"'
                );
            }

            $previousDir = $dir;
            chdir($dir);
        }

        $historyCollection = new HistoryCollection();

        foreach ($this->fileManager->glob(self::HISTORY_PATH . '*.history') as $file) {
            $content = $this->fileManager->fileGetContent($file);

            $historyCollection->append(unserialize($content));
        }

        return $historyCollection;
    }
}
