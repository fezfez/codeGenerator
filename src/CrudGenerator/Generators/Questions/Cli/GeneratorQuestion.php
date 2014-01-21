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
namespace CrudGenerator\Generators\Questions\Cli;

use CrudGenerator\Generators\Finder\GeneratorFinder;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

class GeneratorQuestion
{
    /**
     * @var GeneratorFinder
     */
    private $generatorFinder = null;
    /**
     * @var OutputInterface
     */
    private $output = null;
    /**
     * @var DialogHelper
     */
    private $dialog = null;

    /**
     * @param GeneratorFinder $generatorFinder
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     */
    public function __construct(
        GeneratorFinder $generatorFinder,
        OutputInterface $output,
        DialogHelper $dialog
    ) {
        $this->generatorFinder = $generatorFinder;
        $this->output = $output;
        $this->dialog = $dialog;
    }

    /**
     * Ask wich generator you want to use
     * @param string $defaultGenerator
     * @throws \Exception
     * @return \CrudGenerator\Generators\BaseCodeGenerator
     */
    public function ask($defaultGenerator = null)
    {
        $generatorCollection = $this->generatorFinder->getAllClasses();

        foreach ($generatorCollection as $generatorClassName) {
            if (null !== $defaultGenerator) {
                if ($defaultGenerator === $generatorClassName) {
                    return $generatorClassName;
                }
            } else {
                $generatorsChoices[$generator->getDefinition()] = $generatorClassName;
            }
        }

        if (null !== $defaultGenerator) {
            throw new \Exception(
                sprintf(
                    'Generator "%s" does not exist',
                    $defaultGenerator
                )
            );
        } else {
            $generatorKeysChoices = array_keys($generatorsChoices);

            $choice = $this->dialog->select($this->output, "Choose a generators \n> ", $generatorKeysChoices);

            return $generatorsChoices[$generatorKeysChoices[$choice]];
        }
    }
}
