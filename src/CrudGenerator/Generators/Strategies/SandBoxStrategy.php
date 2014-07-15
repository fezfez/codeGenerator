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
use CrudGenerator\Context\ContextInterface;

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
     * @var ContextInterface Output
     */
    protected $context      = null;

    /**
     * Base code generator
     * @param View $view
     * @param ContextInterface $context
     */
    public function __construct(
        View $view,
        ContextInterface $context
    ) {
        $this->view    = $view;
        $this->context = $context;
    }

    /* (non-PHPdoc)
     * @see CrudGenerator\Generators\Strategies.StrategyInterface::generateFile()
     */
    public function generateFile(array $datas, $skeletonDir, $pathTemplate, $pathTo)
    {
        $continue = true;
        while ($continue) {
            $results = $this->view->render(
                $skeletonDir,
                $pathTemplate,
                $datas
            );

            $this->context->log("<info>[LOG] Generate $pathTo \nfrom $skeletonDir$pathTemplate</info>");
            $this->context->log($results);

            $continue = $this->context->confirm(
                "\n<question>Regenerate ?</question> ",
            	'sanbox_regenerate'
            );
        }
    }
}
