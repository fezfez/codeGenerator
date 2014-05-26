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
use CrudGenerator\FileConflict\FileConflictManagerCli;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Context\CliContext;

/**
 * @author Stéphane Demonchaux
 */
class GeneratorCli
{
    /**
     * @var StrategyInterface
     */
    private $strategy = null;
    /**
     * @var FileConflictManagerCli
     */
    private $fileConflict = null;
    /**
     * @var FileManager
     */
    private $fileManager = null;
    /**
     * @var CliContext
     */
    private $context = null;

    /**
     * @param StrategyInterface $strategy
     */
    public function __construct(StrategyInterface $strategy, FileConflictManagerCli $fileConflict, FileManager $fileManager, CliContext $context)
    {
        $this->strategy = $strategy;
        $this->fileConflict = $fileConflict;
        $this->fileManager = $fileManager;
        $this->context = $context;
    }

    /**
     * @param Generator $generator
     * @param string $fileName
     * @throws \Exception
     * @return string
     */
    public function generate(GeneratorDataObject $generator, $fileName = null)
    {
        $files = $generator->getFiles();

        if (null !== $fileName) {
            $fileToGenerate = null;
            foreach ($files as $file) {
                if ($file['name'] === $fileName) {
                    $fileToGenerate = $file;
                }
            }
            if (null === $fileToGenerate) {
                throw new \InvalidArgumentException('File does not exist');
            }
            return $this->strategy->generateFile(
                $generator->getTemplateVariables(),
                $fileToGenerate['skeletonPath'],
                $fileToGenerate['name'],
                $fileToGenerate['fileName']
            );
        } else {
            foreach ($files as $file) {
                $result = $this->strategy->generateFile(
                    $generator->getTemplateVariables(),
                    $file['skeletonPath'],
                    $file['name'],
                    $file['fileName']
                );

                if ($this->fileConflict->test($file['fileName'], $result)) {
                    $this->fileConflict->handle($file['fileName'], $result);
                } else {
                    $this->fileManager->filePutsContent($file['fileName'], $result);
                    $this->context->log('--> Create file ' . $file['fileName']);
                }
            }
        }

        return $generator;
    }
}