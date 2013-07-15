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

use CrudGenerator\Command\CreateCommand;
use CrudGenerator\Command\UpToDateCommand;

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
     * @return \Symfony\Component\Console\Application
     */
    public static function getInstance()
    {
        $cli = new Application;
        $cli->setName('Code Generator Command Line Interface');

        $cli->addCommands(
            array(
                new CreateCommand(),
                new UpToDateCommand(),
            )
        );

        $helperSet = $cli->getHelperSet();
        $helperSet->set(new DialogHelper(), 'dialog');
        $helperSet->set(new FormatterHelper(), 'formatter');

        return $cli;
    }
}
