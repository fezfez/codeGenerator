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
namespace CrudGenerator\Generators\Installer;

use Symfony\Component\Console\Input\ArrayInput;
use Composer\Command\UpdateCommand;
use CrudGenerator\Utils\OutputWeb;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorInstaller implements GeneratorInstallerInterface
{
    /**
     * @var ArrayInput
     */
    private $input = null;
    /**
     * @var UpdateCommand
     */
    private $updateCommand = null;
    /**
     * @var OutputWeb
     */
    private $output = null;

    /**
     * @param ArrayInput $input
     * @param OutputWeb $output
     */
    public function __construct(ArrayInput $input, UpdateCommand $updateCommand, OutputWeb $output)
    {
        $this->input          = $input;
        $this->updateCommand = $updateCommand;
        $this->output         = $output;
    }

    /**
     * @param string $package
     * @param string $version
     * @return integer
     */
    public function install($package, $version = 'dev-master')
    {
        $this->input->setArgument('packages',  array($package . ':' . $version));
        $this->output->write(sprintf('%s$ composer update %s:%s', getcwd(), $package, $version));

        return $this->updateCommand->run($this->input, $this->output);
    }
}
