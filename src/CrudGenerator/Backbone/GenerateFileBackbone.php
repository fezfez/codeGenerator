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

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Generators\Generator;
use CrudGenerator\Generators\GeneratorDataObject;

class GenerateFileBackbone
{
    /**
     * @var string
     */
    const QUESTION_KEY = 'file_to_generate';
    /**
     * @var Generator
     */
    private $generator = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param Generator        $generator
     * @param ContextInterface $context
     */
    public function __construct(Generator $generator, ContextInterface $context)
    {
        $this->generator = $generator;
        $this->context   = $context;
    }

    /**
     * @param GeneratorDataObject $generator
     */
    public function run(GeneratorDataObject $generator)
    {
        $responseCollection = new PredefinedResponseCollection();

        foreach ($generator->getFiles() as $file) {
            $responseCollection->append(
                new PredefinedResponse($file['name'], $file['name'], $file['name'])
            );
        }

        $question = new QuestionWithPredefinedResponse(
            "Select a file to generate",
            self::QUESTION_KEY,
            $responseCollection
        );
        $question->setShutdownWithoutResponse(true);

        $this->generator->generateFile($generator, $this->context->askCollection($question));
    }
}
