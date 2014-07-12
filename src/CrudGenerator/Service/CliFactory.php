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
namespace CrudGenerator\Service;

use CrudGenerator\Command\CreateCommandFactory;
use CrudGenerator\Context\CliContext;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\FormatterHelper;

/**
 * Create CLI instance
 *
 * @author Stéphane Demonchaux
 */
class CliFactory
{
    /**
     * Create CLI instance
     *
     * @param OutputInterface $output
     * @return \Symfony\Component\Console\Application
     */
    public static function getInstance(OutputInterface $output)
    {
        $dialogHelper   = new DialogHelper();
        $application    = new Application('Code Generator Command Line Interface', 'Alpha');
        $application->getHelperSet()->set(new FormatterHelper(), 'formatter');
        $application->getHelperSet()->set($dialogHelper, 'dialog');

        $context = new CliContext($dialogHelper, $output);

        $application->addCommands(
            array(
                CreateCommandFactory::getInstance($context),
                //CreateByConfigCommandFactory::getInstance($context),
                //CreateByYamlCommandFactory::getInstance($context),
                //GeneratorSandBoxCommandFactory::getInstance($context)
            )
        );

        return $application;
    }
}
