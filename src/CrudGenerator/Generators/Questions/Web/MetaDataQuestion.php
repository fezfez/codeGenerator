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
namespace CrudGenerator\Generators\Questions\Web;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\MetaDataSourceFactory;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;

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
     * @param ContextInterface $context
     */
    public function __construct(
        MetaDataSourceFactory $metaDataSourceFactory,
        ContextInterface $context
    ) {
        $this->metaDataSourceFactory = $metaDataSourceFactory;
        $this->context               = $context;
    }

    /**
     * @param MetaDataSource $metadataSource
     * @return \CrudGenerator\MetaData\Sources\MetaDataDAOCache
     */
    private function getMetaDataDAO(MetaDataSource $metadataSource)
    {
        $metadataSourceFactoryName = $metadataSource->getMetaDataDAOFactory();
        $metadataSourceConfig      = $metadataSource->getConfig();

        return $this->metaDataSourceFactory->create(
            $metadataSourceFactoryName,
            $metadataSourceConfig,
            $this->context->confirm('Retireve metadata without cache', 'metadata_nocache')
        );
    }

    /**
     * Ask wich metadata you want to use
     * @param MetaDataSource $metadataSource
     * @param string|null $choice
     * @throws ResponseExpectedException
     * @return \CrudGenerator\MetaData\DataObject\MetaData
     */
    public function ask(MetaDataSource $metadataSource, $choice = null)
    {
        $metaDataCollection = $this->getMetaDataDAO($metadataSource)->getAllMetadata();
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
