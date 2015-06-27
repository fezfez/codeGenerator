<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\History;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Questions\MetadataSourceConfigured\MetadataSourceConfiguredQuestion;
use CrudGenerator\Generators\Questions\Metadata\MetaDataQuestion;
use CrudGenerator\Generators\ResponseExpectedException;
use CrudGenerator\Metadata\DataObject\MetaDataInterface;
use CrudGenerator\Metadata\MetaDataSource;
use KeepUpdate\ArrayValidator;
use KeepUpdate\ValidationException;

/**
 * History hydrator
 *
 * @author Stéphane Demonchaux
 */
class HistoryHydrator
{
    /**
     * @var MetadataSourceConfiguredQuestion
     */
    private $metadataSourceConfiguredQuestion = null;
    /**
     * @var MetaDataQuestion
     */
    private $metadataQuestion = null;
    /**
     * @var ArrayValidator
     */
    private $arrayValidator = null;

    /**
     * Constructor.
     *
     * @param MetadataSourceConfiguredQuestion $metadataSourceConfiguredQuestion
     * @param MetaDataQuestion                 $metadataQuestion
     * @param ArrayValidator                   $arrayValidator
     */
    public function __construct(
        MetadataSourceConfiguredQuestion $metadataSourceConfiguredQuestion,
        MetaDataQuestion $metadataQuestion,
        ArrayValidator $arrayValidator
    ) {
        $this->metadataSourceConfiguredQuestion = $metadataSourceConfiguredQuestion;
        $this->metadataQuestion                 = $metadataQuestion;
        $this->arrayValidator                   = $arrayValidator;
    }

    /**
     * @param  GeneratorDataObject $dataObject
     * @return string
     */
    public function dtoToJson(GeneratorDataObject $dataObject)
    {
        try {
            $jsonRepresentation = json_encode($dataObject, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            throw ($e->getPrevious()->getPrevious());
        }

        $this->checkIntegrity(json_decode($jsonRepresentation, true));

        return $jsonRepresentation;
    }

    /**
     * @param  array                   $data
     * @throws InvalidHistoryException
     * @return array
     */
    private function checkIntegrity(array $data)
    {
        try {
            return $this->arrayValidator->isValid('\CrudGenerator\Generators\GeneratorDataObject', $data);
        } catch (ValidationException $exception) {
            throw new InvalidHistoryException($exception->getMessage());
        }
    }

    /**
     * @param  string  $content
     * @return History
     */
    public function jsonToDto($content)
    {
        $arrayRepresentation = json_decode($content, true);
        if (null === $arrayRepresentation) {
            throw new InvalidHistoryException(
                "Is not a json string"
            );
        }

        $this->checkIntegrity($arrayRepresentation);

        $dto     = $arrayRepresentation[GeneratorDataObject::DTO];
        $history = new History();

        $history->setName($dto[DataObject::METADATA][MetaDataInterface::NAME]);

        try {
            $metadataSource = $this->metadataSourceConfiguredQuestion->ask(
                $arrayRepresentation[GeneratorDataObject::METADATA_SOURCE][MetaDataSource::UNIQUE_NAME]
            );
        } catch (ResponseExpectedException $e) {
            throw new InvalidHistoryException(
                sprintf(
                    'Metadatasource "%s" does not exist anymore',
                    $arrayRepresentation[GeneratorDataObject::METADATA_SOURCE][MetaDataSource::UNIQUE_NAME]
                )
            );
        }

        try {
            $metadata = $this->metadataQuestion->ask(
                $metadataSource,
                $dto[DataObject::METADATA][MetaDataInterface::NAME]
            );
        } catch (ResponseExpectedException $e) {
            throw new InvalidHistoryException(
                sprintf(
                    'Metadata "%s" does not exist anymore',
                    $dto[DataObject::METADATA][MetaDataInterface::NAME]
                )
            );
        }

        $dataObject = new DataObject();
        $dataObject->setMetadata($metadata);

        foreach ($dto[DataObject::STORE] as $storeKey => $storeValue) {
            $setter = 'set' . ucfirst($storeKey);
            $dataObject->register(array('dtoAttribute' => $storeKey), is_array($storeValue));
            $dataObject->$setter($storeValue);
        }

        $generator = new GeneratorDataObject();
        $generator->setMetadataSource($metadataSource)
                  ->setDto($dataObject)
                  ->setName($arrayRepresentation[GeneratorDataObject::NAME]);

        $history->addDataObject($generator);

        return $history;
    }
}
