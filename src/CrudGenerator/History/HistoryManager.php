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
use CrudGenerator\Utils\FileManager;
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
    const HISTORY_PATH = 'data/crudGenerator/History/';
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

        $fileName = urlencode($dataObject->getEntity()) . '-' . urlencode($dataObject->getGenerator());

        if ($this->fileManager->isFile(self::HISTORY_PATH . $fileName)) {
            $this->fileManager->unlink(self::HISTORY_PATH . $fileName);
        }

        $history = new History();
        $history->setName($dataObject->getEntity())
                ->setDataObject($dataObject);

        $dumpArray = array(
            'package' => array(
                'Logiciel' => array(
                    'PackageFolder' => true,
                    'module' => array(
                        'Tarif' => array(

                        )
                    ),
                    'Generators' =>  $this->dumpToArray($dataObject)
                )
            )
        );

        $yamlRepresentation = \Symfony\Component\Yaml\Yaml::Dump($dumpArray);

        $dumper = new \Symfony\Component\Yaml\Dumper();
        $this->fileManager->filePutsContent(
            self::HISTORY_PATH . $fileName . '.history.yaml',
            $dumper->dump($dumpArray, 2)
        );

        $this->fileManager->filePutsContent(
            self::HISTORY_PATH . $fileName . '.history',
            serialize($history)
        );
    }

    /**
     * @param DataObject $dataObject
     * @param array $array
     * @return array
     */
    private function dumpToArray($dataObject, array $array = array())
    {
        $class = new \ReflectionClass($dataObject);
        $methods = $class->getMethods();

        foreach ($methods as $method) {
            if ($method->getDeclaringClass()->getName() === get_class($dataObject)) {
                $methodName = $method->getName();
                if (substr($methodName, 0, 3) === 'get') {
                    $result = $dataObject->$methodName();
                    $array[get_class($dataObject)]['options'][substr($methodName, 3)] = $result;
                }
            }
        }

        return $array;
    }

    /**
     * Retrieve all history
     * @throws EnvironnementResolverException
     * @return HistoryCollection
     */
    public function findAll()
    {
        if (!$this->fileManager->isDir(self::HISTORY_PATH)) {
            throw new EnvironnementResolverException(
                sprintf(
                    'Unable to locate "%d"',
                    self::HISTORY_PATH
                )
            );
        }

        $historyCollection = new HistoryCollection();

        foreach ($this->fileManager->glob(self::HISTORY_PATH . '*.history') as $file) {
            $content = $this->fileManager->fileGetContent($file);

            $historyCollection->append(unserialize($content));
        }

        return $historyCollection;
    }
}
