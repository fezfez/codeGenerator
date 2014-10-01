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
namespace CrudGenerator\MetaData\Sources\Json;

use CrudGenerator\MetaData\Sources\MetaDataDAOFactoryInterface;
use CrudGenerator\MetaData\Sources\MetaDataConfigInterface;
use CrudGenerator\MetaData\Sources\Json\JsonConfig;
use CrudGenerator\MetaData\MetaDataSource;
use JSONSchema\SchemaGeneratorFactory;
use CrudGenerator\MetaData\Sources\MetaDataDAOFileFactoryInterface;
use CrudGenerator\MetaData\Driver\Web\WebDriver;
use CrudGenerator\MetaData\Driver\DriverConfig;

/**
 * Create Json Metadata DAO instance
 *
 */
class JsonMetaDataDAOFactory implements MetaDataDAOFileFactoryInterface
{
    /**
     * @param MetaDataConfigInterface $config
     * @throws \InvalidArgumentException
     * @return \CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAO
     */
    public static function getInstance(WebDriver $fileDriver, DriverConfig $config)
    {
        $schemaGenerator = SchemaGeneratorFactory::getInstance();

        return new JsonMetaDataDAO(
            $fileDriver,
            $config,
            $schemaGenerator
        );
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
        $dataObject->setDefinition("Json adapter")
                   ->setMetadataDaoFactory('CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory')
                   ->setMetadataDao("CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAO")
                   ->addConnectorFactory('CrudGenerator\MetaData\Driver\Web\WebDriverFactory')
                   ->setUniqueName('Json');

        return $dataObject;
    }
}
