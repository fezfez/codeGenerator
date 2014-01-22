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
namespace CrudGenerator\Generators\Strategies;


use CrudGenerator\View\View;
use Symfony\Component\Console\Output\OutputInterface;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\FileConflict\FileConflictManager;

/**
 * Base code generator, extends it and implement doGenerate method
 * to make you own Generator
 *
 * @author Stéphane Demonchaux
 */
class GeneratorStrategy implements StrategyInterface
{
    /**
     * @var View View manager
     */
    private $view                = null;
    /**
     * @var OutputInterface Output
     */
    private $output              = null;
    /**
     * @var FileManager File Manager
     */
    private $fileManager         = null;
    /**
     * @var FileConflictManager File conflict manager
     */
    private $fileConflictManager = null;

    /**
     * Base code generator
     * @param View $view
     * @param OutputInterface $output
     * @param FileManager $fileManager
     * @param FileConflictManager $fileConflictManager
     */
    public function __construct(
        View $view,
        OutputInterface $output,
        FileManager $fileManager,
        FileConflictManager $fileConflictManager
    ) {
        $this->view                = $view;
        $this->output              = $output;
        $this->fileManager         = $fileManager;
        $this->fileConflictManager = $fileConflictManager;
    }

    /* (non-PHPdoc)
     * @see CrudGenerator\Generators\Strategies.StrategyInterface::generateFile()
     */
    public function generateFile(array $datas, $skeletonDir, $pathTemplate, $target)
    {
        $results = $this->view->render(
            $skeletonDir,
            $pathTemplate,
            $datas
        );

        if (true === $this->fileConflictManager->test($target, $results)) {
            $this->fileConflictManager->handle($target, $results);
        } else {
            $this->fileManager->filePutsContent($target, $results);
            $this->output->writeln('--> Create file ' . $target);
        }
    }

    /* (non-PHPdoc)
     * @see CrudGenerator\Generators\Strategies.StrategyInterface::ifDirDoesNotExistCreate()
     */
    public function ifDirDoesNotExistCreate($dir)
    {
        if (true === $this->fileManager->ifDirDoesNotExistCreate($dir)) {
            $this->output->writeln('--> Create directory ' . $dir);
        }
    }
}