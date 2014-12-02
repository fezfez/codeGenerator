<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Questions\MetadataSource;

use CrudGenerator\Metadata\MetaDataSourceFinder;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;

class MetadataSourceQuestion
{
    const QUESTION_KEY = 'metadatasource';
    /**
     * @var MetaDataSourceFinder
     */
    private $metadataSourceFinder = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param MetaDataSourceFinder $metadataSourceFinder
     * @param ContextInterface     $context
     */
    public function __construct(MetaDataSourceFinder $metadataSourceFinder, ContextInterface $context)
    {
        $this->metadataSourceFinder = $metadataSourceFinder;
        $this->context              = $context;
    }

    /**
     * Ask witch MetaData Source you want to use
     *
     * @param  string                                 $choice
     * @return \CrudGenerator\Metadata\MetaDataSource
     */
    public function ask($choice = null)
    {
        $responseCollection = new PredefinedResponseCollection();

        foreach ($this->metadataSourceFinder->getAllAdapters() as $backend) {
            /* @var $backend \CrudGenerator\Metadata\MetaDataSource */
            if (null === $backend->getFalseDependencies()) {
                $responseCollection->append(
                    new PredefinedResponse($backend->getMetadataDaoFactory(), $backend->getDefinition(), $backend)
                );
            }
        }

        $question = new QuestionWithPredefinedResponse(
            "Select source",
            self::QUESTION_KEY,
            $responseCollection
        );
        $question->setPreselectedResponse($choice);
        $question->setShutdownWithoutResponse(true);

        return $this->context->askCollection($question);
    }
}
