<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Questions\Generator;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\Generators\Finder\GeneratorFinderInterface;
use CrudGenerator\MetaData\DataObject\MetaDataInterface;

class GeneratorQuestion
{
    /**
     * @var string
     */
    const QUESTION_KEY = 'generator';
    /**
     * @var GeneratorFinderInterface
     */
    private $generatorFinder = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param GeneratorFinderInterface $generatorFinder
     * @param ContextInterface         $context
     */
    public function __construct(GeneratorFinderInterface $generatorFinder, ContextInterface $context)
    {
        $this->generatorFinder = $generatorFinder;
        $this->context         = $context;
    }

    /**
     * @param  MetaDataInterface                      $metadata
     * @return \CrudGenerator\Context\ResponseContext
     */
    public function ask(MetaDataInterface $metadata)
    {
        $responseCollection = new PredefinedResponseCollection();
        $generators         = $this->generatorFinder->getAllClasses($metadata);

        foreach ($generators as $name) {
            $responseCollection->append(new PredefinedResponse($name, $name, $name));
        }

        $question = new QuestionWithPredefinedResponse(
            "Select generator",
            self::QUESTION_KEY,
            $responseCollection
        );
        $question->setShutdownWithoutResponse(true);

        return $this->context->askCollection($question);
    }
}
