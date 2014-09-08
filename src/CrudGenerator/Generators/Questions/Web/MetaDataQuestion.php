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

use CrudGenerator\MetaData\Config\MetaDataConfigDAO;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\MetaDataSourceFactory;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\ResponseExpectedException;

class MetaDataQuestion
{
    const QUESTION_KEY = 'metadata';
    /**
     * @var MetaDataConfigDAO
     */
    private $metaDataConfigDAO = null;
    /**
     * @var MetaDataSourceFactory
     */
    private $metaDataSourceFactory = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param MetaDataConfigDAO $metaDataConfigDAO
     * @param MetaDataSourceFactory $metaDataSourceFactory
     */
    public function __construct(
        MetaDataConfigDAO $metaDataConfigDAO,
        MetaDataSourceFactory $metaDataSourceFactory,
        ContextInterface $context
    ) {
        $this->metaDataConfigDAO     = $metaDataConfigDAO;
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
        $metaDataArray      = array();

        foreach ($metaDataCollection as $metaData) {
            $metaDataArray[] = array(
                'id'     => $metaData->getOriginalName(),
                'label'  => $metaData->getOriginalName(),
                'source' => $metadataSource->getUniqueName()
            );
        }

        if (null === $choice) {
            $choice = $this->context->askCollection("Select Metadata", self::QUESTION_KEY, $metaDataArray);
        }

        return $this->retrieve($metadataSource, $choice);
    }

    /**
     * @param MetaDataSource $metadataSource
     * @param string $metaDataNamePreselected
     * @throws ResponseExpectedException
     * @return \CrudGenerator\MetaData\DataObject\MetaData
     */
    public function retrieve(MetaDataSource $metadataSource, $metaDataNamePreselected)
    {
        foreach ($this->getMetaDataDAO($metadataSource)->getAllMetadata() as $metadata) {
            if ($metadata->getOriginalName() === $metaDataNamePreselected) {
                return $metadata;
            }
        }

        throw new ResponseExpectedException(
            sprintf(
                "Metadata %s does not exist",
                $metaDataNamePreselected
            )
        );
    }
}
