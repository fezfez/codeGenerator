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

class Text
{
    /**
     * @param  string  $value
     * @param  boolean $ucFirst
     * @return string
     */
    public function toCamelCase($value, $ucFirst = false)
    {
        $value = preg_replace_callback(
            '/_(\w)/',
            function (array $matches) {
                return ucfirst($matches[1]);
            },
            $value
        );

        if (true === $ucFirst) {
            $value = ucfirst($value);
        }

        return $value;
    }
}
