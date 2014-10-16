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
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\MetaData\Driver\File\FileDriverFactory;
use CrudGenerator\MetaData\Sources\MetaDataDAOFactoryConfigInterface;

/**
 * Create Xml Metadata DAO instance
 *
 */
class XmlMetaDataDAOFactory implements MetaDataDAOFactoryConfigInterface
{
    /**
     * @param MetaDataConfigInterface $config
     * @throws \InvalidArgumentException
     * @return \CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAO
     */
    public static function getInstance(DriverConfig $config)
    {
        $fileDriver = FileDriverFactory::getInstance($config);

        $json = json_encode(
            simplexml_load_string(
                $fileDriver->getFile($config)
            )
        );

        file_put_contents('tmp', $json);

        $jsonConfig = clone $config;
        $jsonConfig->setConfigUrl('tmp');
        $jsonConfig->setDriver('CrudGenerator\MetaData\Driver\File\Web\WebDriverFactory');

        return new XmlMetaDataDAO(JsonMetaDataDAOFactory::getInstance($jsonConfig));
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
                   ->addDriverDescription(\CrudGenerator\MetaData\Driver\File\Web\WebDriverFactory::getDescription())
                   ->setUniqueName('Xml');

        return $dataObject;
    }
}
