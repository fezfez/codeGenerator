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
namespace CrudGenerator\MetaData\Sources\Xml;

use CrudGenerator\MetaData\Sources\MetaDataDAOFactoryInterface;
use CrudGenerator\MetaData\Sources\MetaDataConfigInterface;
use CrudGenerator\MetaData\Sources\Json\JsonConfig;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory;
use CrudGenerator\MetaData\Sources\MetaDataDAOFileFactoryInterface;
use CrudGenerator\MetaData\Driver\Web\WebDriver;

/**
 * Create Xml Metadata DAO instance
 *
 */
class XmlMetaDataDAOFactory implements MetaDataDAOFileFactoryInterface
{
    /**
     * @param MetaDataConfigInterface $config
     * @throws \InvalidArgumentException
     * @return \CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAO
     */
    public static function getInstance(WebDriver $fileDriver, $config)
    {
        $xml   = simplexml_load_string($config->getConnection());
        $json  = json_encode($xml);

        file_put_contents('tmp', $json);

        $jsonConfig = new JsonConfig();
        $jsonConfig->setConfigUrl('tmp');
        $jsonMetadata = JsonMetaDataDAOFactory::getInstance($jsonConfig);

        return new XmlMetaDataDAO($jsonMetadata);
    }

    /**
     * @param MetaDataSource $metadataSource
     * @return boolean
     */
    public static function checkDependencies(MetaDataSource $metadataSource)
    {
        return true;
    }

    /**
     * @return \CrudGenerator\MetaData\MetaDataSource
     */
    public static function getDescription()
    {
        $dataObject = new MetaDataSource();
        $dataObject->setDefinition("Xml")
                   ->setMetadataDaoFactory('CrudGenerator\MetaData\Sources\Xml\XmlMetaDataDAOFactory')
                   ->setMetadataDao("CrudGenerator\MetaData\Sources\Xml\XmlMetaDataDAO")
                   ->addConnectorFactory('CrudGenerator\MetaData\Connector\FileDriver')
                   ->setUniqueName('Xml');

        return $dataObject;
    }
}
