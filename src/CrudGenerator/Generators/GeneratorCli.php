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
     * @param StrategyInterface $strategy
     */
    public function __construct(StrategyInterface $strategy, FileConflictManagerCli $fileConflict, FileManager $fileManager)
    {
        $this->strategy = $strategy;
        $this->fileConflict = $fileConflict;
        $this->fileManager = $fileManager;
    }

    /**
     * @param Generator $generator
     * @param string $fileName
     * @throws \Exception
     * @return string
     */
    public function generate(GeneratorDataObject $generator)
    {
    	$files = $generator->getFiles();

        foreach ($files as $fileName => $file) {
            $result = $this->strategy->generateFile(
                $generator->getTemplateVariables(),
                $file[$fileName]['skeletonPath'],
                $file[$fileName]['name'],
                $file[$fileName]['fileName']
            );

            if ($this->fileConflict->test($file[$fileName]['fileName'], $result)) {
            	$this->fileConflict->handle($file[$fileName]['fileName'], $result);
            } else {
            	$this->fileManager->filePutsContent($file[$fileName]['fileName'], $result);
            	$this->output->writeln('--> Create file ' . $file[$fileName]['fileName']);
            }
        }

        return $generationResult;
    }
}