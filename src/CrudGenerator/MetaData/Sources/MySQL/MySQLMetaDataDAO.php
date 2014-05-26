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

use PDO;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Sources\MySQL\MySQLConfig;
use CrudGenerator\MetaData\Sources\MetaDataDAO;
use CrudGenerator\MetaData\Sources\MySQL\MetadataDataObjectMySQL;
use CrudGenerator\MetaData\DataObject\MetaDataCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

/**
 * MySQL adapter
 */
class MySQLMetaDataDAO implements MetaDataDAO
{
    private $typeConversion = array(
        'character varying' => 'text'
    );
    /**
     * @var PDO Pdo stmt
     */
    private $pdo       = null;
    /**
     * @var MySQLConfig Pdo configuration
     */
    private $pdoConfig = null;

    /**
     * MySQL adapter
     * @param PDO $pdo
     * @param MySQLConfig $pdoConfig
     */
    public function __construct(PDO $pdo, MySQLConfig $pdoConfig)
    {
        $this->pdo        = $pdo;
        $this->pdoConfig  = $pdoConfig;
    }

    /**
     * Get all metadata from MySQL
     *
     * @return \CrudGenerator\MetaData\MetaDataCollection
     */
    public function getAllMetadata()
    {
        $sth = $this->pdo->prepare('show tables');

        $sth->execute();

        return $this->pdoMetadataToGeneratorMetadata(
            $sth->fetchAll(
                PDO::FETCH_COLUMN,
                0
            )
        );
    }

    /**
     * Get particularie metadata from MySQL
     *
     * @param string $tableName
     * @return \CrudGenerator\MetaData\Sources\MySQL\MetadataDataObjectMySQL
     */
    public function getMetadataFor($tableName, array $parentName = array())
    {
    	return $this->hydrateDataObject($tableName);
    }

    /**
     * Convert MySQL mapping to CrudGenerator mapping
     * @param array $metadataCollection
     * @return \CrudGenerator\MetaData\DataObject\MetaDataCollection
     */
    private function pdoMetadataToGeneratorMetadata(array $metadataCollection)
    {
        $metaDataCollection = new MetaDataCollection();

        foreach ($metadataCollection as $tableName) {
            $metaDataCollection->append(
                $this->hydrateDataObject($tableName)
            );
        }

        return $metaDataCollection;
    }

    /**
     * Convert MySQL mapping to CrudGenerator mapping
     * @param string $tableName
     * @return MetadataDataObjectMySQL
     */
    private function hydrateDataObject($tableName)
    {
        $dataObject = new MetadataDataObjectMySQL(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $dataObject->setName($tableName);
        $columnDataObject = new MetaDataColumn();

        $statement = $this->pdo->prepare(
            "SELECT column_name, data_type, column_key, is_nullable
            FROM information_schema.columns
            WHERE table_name = '" . $tableName . "' AND table_schema = '" . $this->pdoConfig->getDatabaseName() . "'"
        );
        $statement->execute();
        $allFields = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($allFields as $metadata) {
            $column = clone $columnDataObject;

            $type = $metadata['data_type'];

            if (isset($this->typeConversion['data_type'])) {
                $type = $this->typeConversion['data_type'];
            }

            $column->setName($metadata['column_name'])
                   ->setType($type)
                   ->setLength(null);
            if ($metadata['column_key'] === 'PRI') {
                $column->setPrimaryKey(true);
            }

            $dataObject->appendColumn($column);
        }

        return $dataObject;
    }
}
