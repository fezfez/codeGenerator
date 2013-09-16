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
namespace CrudGenerator\Command;

use CrudGenerator\Generators\CodeGeneratorStubFactory;
use CrudGenerator\Command\Questions\GeneratorQuestionFactory;

use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;


/**
 * Regenerate command
 *
 * @author Stéphane Demonchaux
 */
class GeneratorSandBoxCommandFactory
{
    /**
     * @param DialogHelper $dialog
     * @param OutputInterface $output
     * @return \CrudGenerator\Command\GeneratorSandBoxCommand
     */
    public static function getInstance(DialogHelper $dialog, OutputInterface $output)
    {
        /*$inputDefinition = new InputDefinition();
        $inputDefinition->addArgument(new InputArgument('filter', InputArgument::OPTIONAL, 'template name'));
        $input->bind($inputDefinition);*/

        $generatorQuestion = GeneratorQuestionFactory::getInstance($dialog, $output, null, true);
        return new GeneratorSandBoxCommand($generatorQuestion);
    }
}
