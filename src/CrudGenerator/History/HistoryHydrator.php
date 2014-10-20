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
     * Constructor.
     *
     * @param MetadataSourceConfiguredQuestion $metadataSourceConfiguredQuestion
     * @param MetadataQuestion $metadataQuestion
     */
    public function __construct(
        MetadataSourceConfiguredQuestion $metadataSourceConfiguredQuestion,
        MetadataQuestion $metadataQuestion
    ) {
        $this->metadataSourceConfiguredQuestion = $metadataSourceConfiguredQuestion;
        $this->metadataQuestion                 = $metadataQuestion;
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
        if (false === $this->isAttributeIsSet($data, 'dto')) {
            throw new InvalidHistoryException(
                "DataObject doesn't have DTO"
            );
        }

        if (false === $this->isAttributeIsSet($data, 'dtoClass')) {
            throw new InvalidHistoryException(
                "DataObject doesn't have DTO"
            );
        }

        if (false === $this->isAttributeIsSet($data['dto'], 'metadata')) {
            throw new InvalidHistoryException(
                "DataObject doesn't have metadata"
            );
        }

        if (false === $this->isAttributeIsSet($data, 'metaDataSource')) {
            throw new InvalidHistoryException(
                "DataObject doesn't have metadataSource"
            );
        }

        if (false === $this->isAttributeIsSet($data['metaDataSource'], 'metaDataDAO')) {
            throw new InvalidHistoryException(
                "MetadataSource is not well configured"
            );
        }

        return $data;
    }

    /**
     * @param array $data
     * @param string $attribute
     * @return boolean
     */
    private function isAttributeIsSet(array $data, $attribute)
    {
        return (false === array_key_exists($attribute, $data) || null === $data[$attribute]) ? false : true;
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

        $dto = $arrayRepresentation['dto'];

        $history = new History();
        $history->setName($dto['metadata']['name']);

        if (false === isset($arrayRepresentation['metaDataSource']['uniqueName'])) {
             throw new InvalidHistoryException('Unique name not set');
        }

        try {
            $metadataSource = $this->metadataSourceConfiguredQuestion->ask(
                $arrayRepresentation['metaDataSource']['uniqueName']
            );
        } catch (ResponseExpectedException $e) {
            throw new InvalidHistoryException(
                sprintf(
                    'Metadatasource "%s" does not exist anymore',
                    $arrayRepresentation['metaDataSource']['uniqueName']
                )
            );
        }

        try {
            $metaData = $this->metadataQuestion->ask($metadataSource, $dto['metadata']['name']);
        } catch (ResponseExpectedException $e) {
            throw new InvalidHistoryException(
                sprintf(
                    'Metadata "%s" does not exist anymore',
                    $arrayRepresentation['metaData']
                )
            );
        }

        $dto = new DataObject();
        $dto->setMetadata($metaData);

        $generator = new GeneratorDataObject();
        $generator->setMetadataSource($metadataSource)
                  ->setDto($dto)
                  ->setName($arrayRepresentation['name']);

        $history->addDataObject($generator);

        return $history;
    }
}
