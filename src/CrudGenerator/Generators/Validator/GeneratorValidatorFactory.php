<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Validator;

use JsonSchema\Constraints\Constraint;
use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator;

/**
 *
 * @author Stéphane Demonchaux
 */
class GeneratorValidatorFactory
{
    /**
     * @return \CrudGenerator\Generators\Validator\GeneratorValidator
     */
    public static function getInstance()
    {
        $retriever = new UriRetriever();
        $schema    = $retriever->retrieve('file://' . realpath(__DIR__ . '/ressources/generator-schema.json'));

        return new GeneratorValidator($schema, new Validator(Constraint::CHECK_MODE_TYPE_CAST));
    }
}
