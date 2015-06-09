<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CrudGenerator\Utils;

class PhpStringParserFactory
{
    /**
     * @return \CrudGenerator\Utils\PhpStringParser
     */
    public static function getInstance()
    {
        return new PhpStringParser(
            new \Twig_Environment(
                new \Twig_Loader_Array(array())
            )
        );
    }
}
