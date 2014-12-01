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

/**
 * @author Stéphane Demonchaux
 */
interface GeneratorInstallerInterface
{
    /**
     * @param  string  $package
     * @param  string  $version
     * @return integer
     */
    public function install($package, $version = 'dev-master');
}
