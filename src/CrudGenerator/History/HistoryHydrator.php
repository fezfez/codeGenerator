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
use CrudGenerator\Generators\Questions\MetadataSourceConfigured\MetadataSourceConfiguredQuestion;
use CrudGenerator\Generators\Questions\Metadata\MetadataQuestion;
use CrudGenerator\Generators\ResponseExpectedException;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\DataObject\MetaDataInterface;
use CrudGenerator\Utils\Comparator;

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
     * @var MetadataQuestion
     */
    private $metadataQuestion = null;
    /**
     * @var Comparator
     */
    private $comparator = null;

    /**
     * Constructor.
     *
     * @param MetadataSourceConfiguredQuestion $metadataSourceConfiguredQuestion
     * @param MetadataQuestion $metadataQuestion
     * @param Comparator $comparator
     */
    public function __construct(
        MetadataSourceConfiguredQuestion $metadataSourceConfiguredQuestion,
        MetadataQuestion $metadataQuestion,
        Comparator $comparator
    ) {
        $this->metadataSourceConfiguredQuestion = $metadataSourceConfiguredQuestion;
        $this->metadataQuestion                 = $metadataQuestion;
        $this->comparator                       = $comparator;
    }

    /**
     * @param GeneratorDataObject $dataObject
     * @return string
     */
    public function dtoToJson(GeneratorDataObject $dataObject)
    {
        $jsonRepresentation = json_encode($dataObject, JSON_PRETTY_PRINT);
        $this->checkIntegrity(json_decode($jsonRepresentation, true));

        return $jsonRepresentation;
    }

    /**
     * @param array $data
     * @throws InvalidHistoryException
     * @return array
     */
    private function checkIntegrity(array $data)
    {
        return $this->comparator->compareClassAndArray('CrudGenerator\Generators\GeneratorDataObject', $data);
    }

    /**
     * @param string $content
     * @return History
     */
    public function jsonToDto($content)
    {
        $jsonRepresentation = json_decode($content, true);
        if (null === $jsonRepresentation) {
            throw new InvalidHistoryException(
                "Is not a json string"
            );
        }
        $arrayRepresentation = $this->checkIntegrity($jsonRepresentation);

        $dto = $arrayRepresentation[GeneratorDataObject::DTO];

        $history = new History();
        $history->setName($dto[DataObject::METADATA][MetaDataInterface::NAME]);

        if (false === isset($arrayRepresentation[GeneratorDataObject::METADATA_SOURCE][MetaDataSource::UNIQUE_NAME])) {
             throw new InvalidHistoryException('Unique name not set');
        }

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
            $metaData = $this->metadataQuestion->ask($metadataSource, $dto[DataObject::METADATA][MetaDataInterface::NAME]);
        } catch (ResponseExpectedException $e) {
            throw new InvalidHistoryException(
                sprintf(
                    'Metadata "%s" does not exist anymore',
                    $dto[DataObject::METADATA][MetaDataInterface::NAME]
                )
            );
        }

        $dto = new DataObject();
        $dto->setMetadata($metaData);

        $generator = new GeneratorDataObject();
        $generator->setMetadataSource($metadataSource)
                  ->setDto($dto)
                  ->setName($arrayRepresentation[GeneratorDataObject::NAME]);

        $history->addDataObject($generator);

        return $history;
    }
}
