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

use CrudGenerator\Context\ContextInterface;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorInstallerProxy implements GeneratorInstallerInterface
{
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param ContextInterface $context
     */
    public function __construct(ContextInterface $context)
    {
        $this->context = $context;
    }

    /**
     * @param  string  $package
     * @param  string  $version
     * @return integer
     */
    public function install($package, $version = 'dev-master')
    {
        $generatorInstaller = GeneratorInstallerFactory::getInstance($this->context);

        return $generatorInstaller->install($package, $version);
    }
}
