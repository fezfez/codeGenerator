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

use CrudGenerator\MetaData\Config\MetaDataConfigReaderForm;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\MetaDataSourceFactory;

class MetaDataQuestion
{
    /**
     * @var MetaDataConfigReaderForm
     */
    private $metaDataConfigReaderForm = null;
    /**
     * @var MetaDataSourceFactory
     */
    private $metaDataSourceFactory = null;

    /**
     * @param MetaDataConfigReaderForm $metaDataConfigReader
     * @param MetaDataSourceFactory $metaDataSourceFactory
     */
    public function __construct(
        MetaDataConfigReaderForm $metaDataConfigReaderForm,
        MetaDataSourceFactory $metaDataSourceFactory
    ) {
        $this->metaDataConfigReaderForm = $metaDataConfigReaderForm;
        $this->metaDataSourceFactory = $metaDataSourceFactory;
    }

    /**
     * Ask wich metadata you want to use
     * @param MetaDataSource $adapter
     * @param string $default
     */
    public function ask(MetaDataSource $metadataSource, $metaDataNamePreselected = null)
    {
        $metadataSourceFactoryName = $metadataSource->getFactory();
        $metadataSourceConfig      = $metadataSource->getConfig();

        if (null !== $metadataSourceConfig) {
            $metadataSourceConfig = $this->metaDataConfigReaderForm->config($metadataSourceConfig);
        }

        $metaDataDAO = $this->metaDataSourceFactory->create($metadataSourceFactoryName, $metadataSourceConfig);

        $metaDataChoices = array();
        foreach ($metaDataDAO->getAllMetadata() as $metadata) {
            $metaDataChoices[] = array('id' => $metadata->getOriginalName(), 'label' => $metadata->getOriginalName());
        }

        if (null !== $metaDataNamePreselected) {
            foreach ($metaDataDAO->getAllMetadata() as $metadata) {
                if ($metadata->getOriginalName() === $metaDataNamePreselected) {
                    return $metadata;
                }
            }
        }

        return $metaDataChoices;
    }
}
