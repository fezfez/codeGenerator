<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Questions\MetadataSourceConfigured;

use CrudGenerator\MetaData\Config\MetaDataConfigDAO;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;

class MetadataSourceConfiguredQuestion
{
    /**
     * @var string
     */
    const QUESTION_KEY = 'backend';
    /**
     * @var MetaDataConfigDAO
     */
    private $metadataSourceConfigDAO = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param MetaDataConfigDAO $metadataSourceConfigDAO
     * @param ContextInterface $context
     */
    public function __construct(MetaDataConfigDAO $metadataSourceConfigDAO, ContextInterface $context)
    {
        $this->metadataSourceConfigDAO = $metadataSourceConfigDAO;
        $this->context                 = $context;
    }

    /**
     * Ask witch MetaData Source you want to use
     *
     * @param string $choice
     * @throws ResponseExpectedException
     * @return \CrudGenerator\MetaData\MetaDataSource
     */
    public function ask($choice = null)
    {
        $responseCollection         = new PredefinedResponseCollection();
        $sourceConfiguredCollection = $this->metadataSourceConfigDAO->retrieveAll();

        if ($sourceConfiguredCollection->count() === 0) {
            throw new \Exception('You must config a metadataSource');
        }

        foreach ($sourceConfiguredCollection as $backend) {
            /* @var $backend \CrudGenerator\MetaData\MetaDataSource */
            if(null === $backend->getFalseDependencies()) {
                $responseCollection->append(
                    new PredefinedResponse($backend->getUniqueName(), $backend->getUniqueName(), $backend)
                );
            }
        }

        $question = new QuestionWithPredefinedResponse(
            "Select configured source",
            self::QUESTION_KEY,
            $responseCollection
        );
        $question->setPreselectedResponse($choice);
        $question->setShutdownWithoutResponse(true);

        return $this->context->askCollection($question);
    }
}
