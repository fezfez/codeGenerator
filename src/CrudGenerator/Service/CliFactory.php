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
use CrudGenerator\Command\CreateByConfigCommandFactory;
use CrudGenerator\Command\RegenerateCommandFactory;
use CrudGenerator\Command\GeneratorSandBoxCommandFactory;

use Symfony\Component\Console\Input\InputInterface;
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
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return \Symfony\Component\Console\Application
     */
    public static function getInstance(InputInterface $input, OutputInterface $output)
    {
        $dialogHelper   = new DialogHelper();
        $application    = new Application('Code Generator Command Line Interface', 'Alpha');
        $application->getHelperSet()->set(new FormatterHelper(), 'formatter');
        $application->getHelperSet()->set($dialogHelper, 'dialog');

        $application->addCommands(
            array(
                CreateCommandFactory::getInstance($dialogHelper, $output),
                CreateByConfigCommandFactory::getInstance($dialogHelper, $output),
                RegenerateCommandFactory::getInstance($dialogHelper, $output),
                GeneratorSandBoxCommandFactory::getInstance($dialogHelper, $output)
            )
        );

        return $application;
    }
}
