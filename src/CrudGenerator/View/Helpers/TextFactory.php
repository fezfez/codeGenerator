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

class TextFactory implements ViewHelperFactoryInterface
{
    /**
     * @return Text
     */
    public static function getInstance()
    {
        return new Text();
    }
}
