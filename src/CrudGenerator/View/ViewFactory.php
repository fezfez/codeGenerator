<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\View;

use CrudGenerator\Utils\ClassAwake;

/**
 * View manager factory
 *
 * @author Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 */
class ViewFactory
{
    /**
     * Build a View manager
     *
     * @return View
     */
    public static function getInstance()
    {
        $classAwake  = new ClassAwake();
        $viewHelpers = $classAwake->wakeByInterfaces(
            array(
                __DIR__.'/../',
            ),
            'CrudGenerator\View\ViewHelperFactoryInterface'
        );

        $viewRenderer = new ViewRenderer($viewHelpers);

        return new View($viewRenderer);
    }
}
