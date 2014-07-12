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
namespace CrudGenerator\Generators;

use CrudGenerator\Generators\Strategies\StrategyInterface;
use CrudGenerator\FileConflict\FileConflictManager;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Context\CliContext;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\History\HistoryManager;

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
     * @var CliContext
     */
    private $context = null;

    /**
     * @param StrategyInterface $strategy
     * @param FileConflictManager $fileConflict
     * @param FileManager $fileManager
     * @param HistoryManager $historyManager
     * @param ContextInterface $context
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
     * @param Generator $generator
     * @param string $fileName
     * @throws \Exception
     * @return string
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

            if ($this->fileConflict->test($file['fileName'], $result)) {
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
     * @param GeneratorDataObject $generator
     * @param string $fileName
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
