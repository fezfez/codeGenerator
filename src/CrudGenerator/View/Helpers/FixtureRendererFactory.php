<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\View\Helpers;

use CrudGenerator\View\ViewHelperFactoryInterface;
use Faker\Factory;

class FixtureRendererFactory implements ViewHelperFactoryInterface
{
    /**
     * @return \CrudGenerator\View\Helpers\FixtureRenderer
     */
    public static function getInstance()
    {
        $faker = Factory::create();

        return new FixtureRenderer($faker);
    }
}
