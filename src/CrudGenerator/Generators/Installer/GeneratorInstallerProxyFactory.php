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
 *
 * @author Stéphane Demonchaux
 */
class GeneratorInstallerProxyFactory
{
    /**
     * @param  ContextInterface                                            $context
     * @return \CrudGenerator\Generators\Installer\GeneratorInstallerProxy
     */
    public static function getInstance(ContextInterface $context)
    {
        return new GeneratorInstallerProxy(
            $context
        );
    }
}
