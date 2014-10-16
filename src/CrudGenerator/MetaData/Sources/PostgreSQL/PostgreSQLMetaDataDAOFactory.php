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
namespace CrudGenerator\MetaData\Sources\PostgreSQL;

use CrudGenerator\MetaData\Sources\PostgreSQL\SqlManager;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Driver\Pdo\PdoDriver;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\MetaData\Driver\Pdo\PdoDriverFactory;
use CrudGenerator\MetaData\Sources\MetaDataDAOFactoryConfigInterface;

/**
 * Create PostgreSQL Metadata DAO instance
 *
 */
class PostgreSQLMetaDataDAOFactory implements MetaDataDAOFactoryConfigInterface
{
    /**
     * Create PostgreSQL Metadata DAO instance
     *
     * @return PostgreSQLMetaDataDAO
     */
    public static function getInstance(DriverConfig $config)
    {
        $pdoDriver = PdoDriverFactory::getInstance();

        return new PostgreSQLMetaDataDAO(
            $pdoDriver->getConnection($config),
            $config,
            new SqlManager()
        );
    }

    /**
     * @param MetaDataSource $metadataSource
     * @return boolean
     */
    public static function checkDependencies(MetaDataSource $metadataSource)
    {
        $isLoaded = extension_loaded('pdo_pgsql');
        if (false === $isLoaded) {
            $metadataSource->setFalseDependencie('The extension "pdo_pgsql" is not loaded');
        }

        return $isLoaded;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataDAO::getDataObject()
    */
    public static function getDescription()
    {
        $pdoDriver = \CrudGenerator\MetaData\Driver\Pdo\PdoDriverFactory::getDescription();
        $pdoDriver->getConfig()->response('dsn', \CrudGenerator\MetaData\Driver\Pdo\PdoDriver::POSTGRESQL);

        $dataObject = new MetaDataSource();
        $dataObject->setDefinition("PostgreSQL")
                   ->setMetadataDaoFactory('CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory')
                   ->setMetadataDao("CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAO")
                   ->addDriverDescription($pdoDriver)
                   ->setUniqueName('PostgreSQL');

        return $dataObject;
    }
}
