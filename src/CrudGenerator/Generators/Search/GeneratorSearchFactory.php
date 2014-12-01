<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Search;

use CrudGenerator\Context\ContextInterface;
use Packagist\Api\Client;

/**
 *
 * @author Stéphane Demonchaux
 */
class GeneratorSearchFactory
{
    /**
     * @param  ContextInterface $context
     * @return GeneratorSearch
     */
    public static function getInstance(ContextInterface $context)
    {
        return new GeneratorSearch(
            new Client(),
            $context
        );
    }
}
