<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Questions\Metadata;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Metadata\MetaDataSource;
use CrudGenerator\Metadata\MetaDataSourceFactory;

class MetaDataQuestion
{
    const QUESTION_KEY = 'metadata';
    /**
     * @var MetaDataSourceFactory
     */
    private $metaDataSourceFactory = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param MetaDataSourceFactory $metaDataSourceFactory
     * @param ContextInterface      $context
     */
    public function __construct(
        MetaDataSourceFactory $metaDataSourceFactory,
        ContextInterface $context
    ) {
        $this->metaDataSourceFactory = $metaDataSourceFactory;
        $this->context               = $context;
    }

    /**
     * @param  MetaDataSource                                   $metadataSource
     * @return \CrudGenerator\Metadata\Sources\MetaDataDAOCache
     */
    private function getMetadataDao(MetaDataSource $metadataSource)
    {
        $metadataSourceFactoryName = $metadataSource->getMetadataDaoFactory();
        $metadataSourceConfig      = $metadataSource->getConfig();

        return $this->metaDataSourceFactory->create(
            $metadataSourceFactoryName,
            $metadataSourceConfig,
            $this->context->confirm('Retireve metadata without cache', 'metadata_nocache')
        );
    }

    /**
     * Ask wich metadata you want to use
     * @param  MetaDataSource                              $metadataSource
     * @param  string|null                                 $choice
     * @throws ResponseExpectedException
     * @return \CrudGenerator\Metadata\DataObject\MetaData
     */
    public function ask(MetaDataSource $metadataSource, $choice = null)
    {
        $metaDataCollection = $this->getMetadataDao($metadataSource)->getAllMetadata();
        $responseCollection = new PredefinedResponseCollection();

        foreach ($metaDataCollection as $metaData) {
            $response = new PredefinedResponse($metaData->getOriginalName(), $metaData->getOriginalName(), $metaData);
            $response->setAdditionalData(array('source' => $metadataSource->getUniqueName()));

            $responseCollection->append($response);
        }

        $question = new QuestionWithPredefinedResponse(
            "Select Metadata",
            self::QUESTION_KEY,
            $responseCollection
        );
        $question->setPreselectedResponse($choice);
        $question->setShutdownWithoutResponse(true);

        return $this->context->askCollection($question);
    }
}
