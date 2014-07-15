<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
namespace CrudGenerator\History;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\Questions\Web\MetaDataSourcesConfiguredQuestion;
use CrudGenerator\Generators\Questions\Web\MetaDataQuestion;
use CrudGenerator\Generators\ResponseExpectedException;
use CrudGenerator\Generators\GeneratorDataObject;

/**
 * HistoryManager instance
 *
 * @author Stéphane Demonchaux
 */
class HistoryHydrator
{
    /**
     * @var MetaDataSourcesConfiguredQuestion
     */
    private $metaDataSourcesConfiguredQuestion = null;
    /**
     * @var MetaDataQuestion
     */
    private $metaDataQuestion = null;

    /**
     * @param MetaDataSourcesConfiguredQuestion $metaDataSourcesConfiguredQuestion
     * @param MetaDataQuestion $metaDataQuestion
     */
    public function __construct(
        MetaDataSourcesConfiguredQuestion $metaDataSourcesConfiguredQuestion,
        MetaDataQuestion $metaDataQuestion
    ) {
        $this->metaDataSourcesConfiguredQuestion = $metaDataSourcesConfiguredQuestion;
        $this->metaDataQuestion                  = $metaDataQuestion;
    }

    /**
     * @param GeneratorDataObject $dataObject
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

        try {
            $metadataSource = $this->metaDataSourcesConfiguredQuestion->ask($arrayRepresentation['metaDataSource']['config']['uniqueName']);
        } catch (ResponseExpectedException $e) {
            throw new InvalidHistoryException(
                sprintf(
                    'Metadatasource "%s" does not exist anymore',
                    $arrayRepresentation['metaDataSource']['config']['uniqueName']
                )
            );
        }

        try {
            $metaData = $this->metaDataQuestion->ask($metadataSource, $dto['metadata']['name']);
        } catch (ResponseExpectedException $e) {
            throw new InvalidHistoryException(
                sprintf(
                    'Metadata "%s" does not exist anymore',
                    $arrayRepresentation['metaData']
                )
            );
        }

        $dtoClass = $arrayRepresentation['dtoClass'];
        $dto      = new $dtoClass();
        $dto->setMetadata($metaData);

        $generator = new GeneratorDataObject();
        $generator->setMetadataSource($metadataSource)
                  ->setDTO($dto)
                  ->setName($arrayRepresentation['name']);

        $history->addDataObject($generator);

        return $history;
    }
}
