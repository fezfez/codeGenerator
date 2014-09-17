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

use CrudGenerator\MetaData\Sources\MetadataConfigDatabase;
use CrudGenerator\MetaData\Sources\MetaDataConfigInterface;
use CrudGenerator\MetaData\Config\ConfigException;

/**
 * PostgreSQL configuration for PostgreSQL Metadata adapter
 *
 * @author Stéphane Demonchaux
 */
class PostgreSQLConfig extends MetadataConfigDatabase implements MetaDataConfigInterface, \JsonSerializable
{
    /**
     * @var string Config definition
     */
    private $definition = 'PostgreSQL adapter';
    /**
     * @var string
     */
    private $metaDataDAOFactory = 'CrudGenerator\MetaData\Sources\PgSQL\PgSQLMetaDataDAOFactory';

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfigInterface::test()
     */
    public function test()
    {
        try {
            $this->getConnection();
        } catch (\PDOException $e) {
            throw new ConfigException('Connection failed with "' . $e->getMessage() . '"');
        }
    }
    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfigInterface::getDefinition()
     */
    public function getDefinition()
    {
        return $this->definition;
    }
    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfigInterface::getUniqueName()
     */
    public function getUniqueName()
    {
        return 'PostgreSQL ' . $this->configHost . ' ' . $this->configUser;
    }
    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfigInterface::getConnection()
     */
    public function getConnection()
    {
        $pdo = new \PDO(
            'pgsql:dbname=' . $this->configDatabaseName . ';host=' . $this->configHost,
            $this->configUser,
            $this->configPassword
        );

        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfigInterface::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return array(
            'configDatabaseName' => $this->configDatabaseName,
            'configHost'         => $this->configHost,
            'configUser'         => $this->configUser,
            'configPassword'     => $this->configPassword,
            'configPassword'     => $this->configPort,
            'metaDataDAOFactory' => $this->metaDataDAOFactory,
        );
    }

    /**
     * Get MetaDataDAOFactory
     *
     * @return string
     */
    public function getMetadataDaoFactory()
    {
        return $this->metaDataDAOFactory;
    }
}
