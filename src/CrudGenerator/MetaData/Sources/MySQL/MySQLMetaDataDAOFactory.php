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
use CrudGenerator\MetaData\Sources\MySQL\MySQLConfig;

/**
 * Create MySQL Metadata DAO instance
 *
 */
class MySQLMetaDataDAOFactory implements MetaDataDAOFactoryInterface
{
    /**
     * Create MySQL Metadata DAO instance
     *
     * @param MySQLConfig $config
     * @return MySQLMetaDataDAO
     */
    public static function getInstance(MetaDataConfigInterface $config = null)
    {
        if (false === ($config instanceof MySQLConfig)) {
            throw new \InvalidArgumentException('Config must be an instance of MySQLConfig');
        }

        return new MySQLMetaDataDAO(
            $config->getConnection(),
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
                   ->setMetaDataDAOFactory('CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory')
                   ->setMetaDataDAO("CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAO")
                   ->setConfig(new MySQLConfig());

        return $dataObject;
    }
}
