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
namespace CrudGenerator\MetaData\Sources\Oracle;

use CrudGenerator\MetaData\Sources\MetaDataDAOFactoryInterface;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Sources\MetaDataConfigInterface;
use CrudGenerator\MetaData\Sources\Oracle\OracleConfig;
use CrudGenerator\MetaData\Sources\MetaDataDAOPdoFactoryInterface;
use CrudGenerator\MetaData\Driver\Pdo\PdoDriver;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\MetaData\Driver\Pdo\PdoDriverFactory;
use CrudGenerator\MetaData\Sources\MetaDataDAOFactoryConfigInterface;

/**
 * Create PDO Metadata DAO instance
 *
 */
class OracleMetaDataDAOFactory implements MetaDataDAOFactoryConfigInterface
{
    /**
     * Create PDO Metadata DAO instance
     *
     * @param MetaDataConfigInterface $config
     * @return OracleMetaDataDAO
     */
    public static function getInstance(DriverConfig $config)
    {
        $pdoDriver = PdoDriverFactory::getInstance();

        return new OracleMetaDataDAO(
            $pdoDriver->getConnection($config, PdoDriver::ORACLE)
        );
    }

    /**
     * @param MetaDataSource $metadataSource
     * @return boolean
     */
    public static function checkDependencies(MetaDataSource $metadataSource)
    {
        $isLoaded = extension_loaded('pdo_oci');
        if (false === $isLoaded) {
            $metadataSource->setFalseDependencie('The extension "pdo_oci" is not loaded');
        }

        return $isLoaded;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataDAO::getDataObject()
    */
    public static function getDescription()
    {
        $pdoDriver = \CrudGenerator\MetaData\Driver\Pdo\PdoDriverFactory::getDescription();
        $pdoDriver->getConfig()->response('dsn', \CrudGenerator\MetaData\Driver\Pdo\PdoDriver::ORACLE);

        $dataObject = new MetaDataSource();
        $dataObject->setDefinition("Oracle")
                   ->setMetadataDaoFactory('CrudGenerator\MetaData\Sources\Oracle\OracleMetaDataDAOFactory')
                   ->setMetadataDao('CrudGenerator\MetaData\Sources\Oracle\OracleMetaDataDAO')
                   ->addDriverDescription($pdoDriver)
                   ->setUniqueName('Oracle');

        return $dataObject;
    }
}
