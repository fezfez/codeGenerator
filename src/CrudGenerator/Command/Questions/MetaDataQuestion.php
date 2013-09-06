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
namespace CrudGenerator\Command\Questions;

use CrudGenerator\MetaData\Config\MetaDataConfigReader;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\MetaDataSourceFactory;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

class MetaDataQuestion
{
    /**
     * @var MetaDataConfigReader
     */
    private $metaDataConfigReader = null;
    /**
     * @var MetaDataSourceFactory
     */
    private $metaDataSourceFactory = null;
    /**
     * @var OutputInterface
     */
    private $output = null;
    /**
     * @var DialogHelper
     */
    private $dialog = null;
    /**
     * @param MetaDataSourceFinder $metaDataConfigReader
     * @param MetaDataSourceFactory $metaDataSourceFactory
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     */
    public function __construct(
        MetaDataConfigReader $metaDataConfigReader,
        MetaDataSourceFactory $metaDataSourceFactory,
        OutputInterface $output,
        DialogHelper $dialog
    ) {
        $this->metaDataConfigReader = $metaDataConfigReader;
        $this->metaDataSourceFactory = $metaDataSourceFactory;
        $this->output = $output;
        $this->dialog = $dialog;
    }

    /**
     * Ask wich metadata you want to use
     * @param MetaDataSource $adapter
     */
    public function ask(MetaDataSource $metadataSource)
    {
        $metadataSourceFactoryName = $metadataSource->getFactory();
        $metadataSourceConfig      = $metadataSource->getConfig();

        if (null !== $metadataSourceConfig) {
            $metadataSourceConfig = $this->metaDataConfigReader->config($metadataSourceConfig);
        }

        $metaDataDAO = $this->metaDataSourceFactory->create($metadataSourceFactoryName, $metadataSourceConfig);

        $metaDataChoices = array();
        foreach ($metaDataDAO->getAllMetadata() as $metaData) {
            $metaDataChoices[] = $metaData->getName();
        }

        $metaDataName = $this->dialog->select(
            $this->output,
            "<question>Full namespace Metadata</question> \n> ",
            $metaDataChoices
        );

        return $metaDataDAO->getMetadataFor(
            $metaDataChoices[$metaDataName]
        );
    }
}
