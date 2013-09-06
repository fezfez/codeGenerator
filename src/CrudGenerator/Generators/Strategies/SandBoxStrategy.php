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

use CrudGenerator\DataObject;
use CrudGenerator\View\View;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

/**
 * Base code generator, extends it and implement doGenerate method
 * to make you own Generator
 *
 * @author Stéphane Demonchaux
 */
class SandBoxStrategy implements StrategyInterface
{
    /**
     * @var View View manager
     */
    protected $view                = null;
    /**
     * @var OutputInterface Output
     */
    protected $clientResponse      = null;
    /**
     * @var DialogHelper Dialog
     */
    protected $dialog              = null;

    /**
     * Base code generator
     * @param View $view
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     */
    public function __construct(
        View $view,
        OutputInterface $output,
        DialogHelper $dialog
    ) {
        $this->view   = $view;
        $this->output = $output;
        $this->dialog = $dialog;
    }

    /* (non-PHPdoc)
     * @see CrudGenerator\Generators\Strategies.StrategyInterface::generateFile()
     */
    public function generateFile(DataObject $dataObject, $skeletonDir, $pathTemplate, $pathTo, array $suppDatas = array())
    {
        $datas = array(
            'dir'        => $skeletonDir,
            'dataObject' => $dataObject,
        );

        $continue = true;
        while ($continue) {
            $results = $this->view->render(
                $skeletonDir,
                $pathTemplate,
                array_merge($datas, $suppDatas)
            );

            $this->output->writeln("<info>[LOG] Generate $pathTo \nfrom $skeletonDir$pathTemplate</info>");
            $this->output->writeln($results);

            $continue = $this->dialog->askConfirmation(
                $this->output,
                "\n<question>Regenerate ?</question> "
            );
        }
    }

    /* (non-PHPdoc)
     * @see CrudGenerator\Generators\Strategies.StrategyInterface::ifDirDoesNotExistCreate()
     */
    public function ifDirDoesNotExistCreate($dir)
    {
        $this->output->writeln('<info>[LOG] --> Create dir ' . $dir . '</info>');
    }
}