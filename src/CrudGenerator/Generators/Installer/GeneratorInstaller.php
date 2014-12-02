<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Installer;

use Composer\Command\RequireCommand;
use CrudGenerator\Utils\OutputWeb;
use Symfony\Component\Console\Input\ArrayInput;

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
     * @var RequireCommand
     */
    private $requireCommand = null;
    /**
     * @var OutputWeb
     */
    private $output = null;

    /**
     * @param ArrayInput $input
     * @param OutputWeb  $output
     */
    public function __construct(
        ArrayInput $input,
        RequireCommand $requireCommand,
        OutputWeb $output
    ) {
        $this->input          = $input;
        $this->requireCommand = $requireCommand;
        $this->output         = $output;
    }

    /**
     * @param  string  $package
     * @param  string  $version
     * @return integer
     */
    public function install($package, $version = 'dev-master')
    {
        $this->input->setArgument('packages', array($package . ':' . $version));
        $this->output->write(sprintf('%s$ composer require %s:%s --no-update', getcwd(), $package, $version));

        return $this->requireCommand->run($this->input, $this->output);
    }
}
