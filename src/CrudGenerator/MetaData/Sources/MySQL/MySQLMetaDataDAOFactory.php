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
namespace CrudGenerator\MetaData\Sources\MySQL;

use CrudGenerator\MetaData\Sources\MetaDataDAOFactoryInterface;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Sources\MetaDataConfigInterface;
use CrudGenerator\MetaData\Sources\MetaDataDAOPdoFactoryInterface;
use CrudGenerator\MetaData\Driver\Pdo\PdoDriver;
use CrudGenerator\MetaData\Driver\DriverConfig;

/**
 * Create MySQL Metadata DAO instance
 *
 */
class MySQLMetaDataDAOFactory implements MetaDataDAOPdoFactoryInterface
{
    /**
     * Create MySQL Metadata DAO instance
     *
     * @return MySQLMetaDataDAO
     */
    public static function getInstance(PdoDriver $pdoDriver, DriverConfig $config)
    {
        return new MySQLMetaDataDAO(
            $pdoDriver->getConnection($config),
            $config
        );
    }

    /**
     * @param MetaDataSource $metadataSource
     * @return boolean
     */
    public static function checkDependencies(MetaDataSource $metadataSource)
    {
        $isLoaded = extension_loaded('pdo_mysql');
        if (false === $isLoaded) {
            $metadataSource->setFalseDependencie('The extension "pdo_mysql" is not loaded');
        }

        return $isLoaded;
    }

    /**
     * @return \CrudGenerator\MetaData\MetaDataSource
     */
    public static function getDescription()
    {
        $dataObject = new MetaDataSource();
        $dataObject->setDefinition("MySQL")
                   ->setMetadataDaoFactory('CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory')
                   ->setMetadataDao("CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAO")
                   ->addConnectorFactory("CrudGenerator\MetaData\Connector\PdoConnectorFactory")
                   ->setUniqueName("MySQL");

        return $dataObject;
    }
}
