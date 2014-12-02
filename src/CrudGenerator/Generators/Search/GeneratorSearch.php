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
use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\SimpleQuestion;
use Packagist\Api\Client;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorSearch
{
    /**
     * @var string
     */
    const QUESTION_KEY = 'search_generatorcollection';
    /**
     * @var Client
     */
    private $packagistApiClient = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param Client           $packagistApiClient
     * @param ContextInterface $context
     */
    public function __construct(Client $packagistApiClient, ContextInterface $context)
    {
        $this->packagistApiClient = $packagistApiClient;
        $this->context            = $context;
    }

    /**
     * @return \Packagist\Api\Result\Result
     */
    public function ask()
    {
        $name = $this->context->ask(new SimpleQuestion('Search generator', 'generator_name'));
        $list = $this->packagistApiClient->search($name, array('type' => 'fezfez-code-generator'));

        $responseCollection = new PredefinedResponseCollection();

        foreach ($list as $package) {
            /* @var \Packagist\Api\Result\Result $package */
            $predefinedResponse = new PredefinedResponse(
                $package->getName(),
                $package->getDescription(),
                $package
            );
            $predefinedResponse->setAdditionalData(array('repository' => $package->getRepository()));
            $responseCollection->append($predefinedResponse);
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
