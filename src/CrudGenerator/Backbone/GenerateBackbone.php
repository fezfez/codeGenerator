<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Backbone;

use CrudGenerator\Generators\Generator;
use CrudGenerator\Generators\GeneratorDataObject;

class GenerateBackbone
{
    /**
     * @var Generator
     */
    private $generator = null;

    /**
     * @param Generator $generator
     */
    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @param GeneratorDataObject $generator
     */
    public function run(GeneratorDataObject $generator)
    {
        $this->generator->generate($generator);
    }
}
