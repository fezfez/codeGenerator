<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Detail;

use CrudGenerator\Context\ContextInterface;
use Github\Client;
use Github\Api\Repo;
use Github\Api\Markdown;

/**
 *
 * @author Stéphane Demonchaux
 */
class GeneratorDetailFactory
{
    /**
     * @param ContextInterface $context
     * @return GeneratorDetail
     */
    public static function getInstance(ContextInterface $context)
    {
        $client = new Client();

        return new GeneratorDetail(
            new Repo($client),
            new Markdown($client)
        );
    }
}
