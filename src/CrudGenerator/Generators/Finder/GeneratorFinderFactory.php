<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Finder;

use CrudGenerator\Generators\Validator\GeneratorValidatorFactory;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\Installer;
use CrudGenerator\Utils\TranstyperFactory;

/**
 * Create GeneratorFinder instance
 *
 * @author Stéphane Demonchaux
 */
class GeneratorFinderFactory
{
    /**
     * Create GeneratorFinder instance
     *
     * @return GeneratorFinderCache
     */
    public static function getInstance()
    {
        return new GeneratorFinderCache(
            new GeneratorFinder(
                TranstyperFactory::getInstance(),
                GeneratorValidatorFactory::getInstance(),
                new FileManager()
            ),
            Installer::getDirectories(),
            new FileManager()
        );
    }
}
