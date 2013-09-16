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

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\GeneratorDependenciesFactory;
use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\Generators\Strategies\StrategyInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

/**
 * Create CodeGenerator instance
 * @author Stéphane Demonchaux
 */
class CodeGeneratorFactory
{
    /**
     * @var StrategyInterface
     */
    private $strategy = null;

    /**
     * @param StrategyInterface $strategy
     */
    public function __construct(StrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }
    /**
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @param StrategyInterface $strategy
     * @param string $class
     * @return CrudGenerator\Generators\BaseCodeGenerator
     */
    public function create(OutputInterface $output, DialogHelper $dialog, $class)
    {
        $fileManager           = new FileManager();
        $generiqueQuestion     = new GeneriqueQuestions($dialog, $output, $fileManager);
        $stub = false;
        if ($this->strategy instanceof \CrudGenerator\Generators\Strategies\SandBoxStrategy) {
            $stub = true;
        }
        $generatorDependencies = GeneratorDependenciesFactory::getInstance($dialog, $output, $stub);

        return new $class($output, $dialog, $generiqueQuestion, $this->strategy, $generatorDependencies);
    }
}
