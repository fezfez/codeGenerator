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

use PDO;
use CrudGenerator\MetaData\Sources\PDO\PDOConfig;
use CrudGenerator\MetaData\Sources\PDO\SqlManager;
use CrudGenerator\MetaData\Sources\MetaDataDAOInterface;
use CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO;
use CrudGenerator\MetaData\DataObject\MetaDataCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

/**
 * Oracle adapter
 */
class OracleMetaDataDAO implements MetaDataDAOInterface
{
    /**
     * @var PDO Pdo stmt
     */
    private $pdo       = null;
    /**
     * @var OracleConfig Pdo configuration
     */
    private $oracleConfig = null;

    /**
     * PDO adapter
     * @param PDO $pdo
     * @param PDOConfig $pdoConfig
     */
    public function __construct(PDO $pdo, OracleConfig $pdoConfig)
    {
        $this->pdo        = $pdo;
        $this->pdoConfig  = $pdoConfig;
    }

    /**
     * Get all metadata from PDO
     *
     * @return \CrudGenerator\MetaData\MetaDataCollection
     */
    public function getAllMetadata()
    {
        $sth = $this->pdo->prepare(
			"select OWNER , TABLE_NAME as TABLE_NAME
			from SYS.ALL_TABLES
			where TABLESPACE_NAME = 'TBLSP_DATA' and OWNER not like 'SYS%'
			order by OWNER, TABLE_NAME"
        );

        $sth->execute();

        return $this->pdoMetadataToGeneratorMetadata(
            $sth->fetchAll(
                PDO::FETCH_ASSOC
            )
        );
    }

    /**
     * Convert PDOmapping to CrudGenerator mapping
     * @param array $metadataCollection
     * @return \CrudGenerator\MetaData\DataObject\MetaDataCollection
     */
    private function pdoMetadataToGeneratorMetadata(array $metadataCollection)
    {
        $metaDataCollection = new MetaDataCollection();

        foreach ($metadataCollection as $data) {
            $metaDataCollection->append(
                $this->hydrateDataObject($data)
            );
        }

        return $metaDataCollection;
    }

    /**
     * Convert PDOmapping to CrudGenerator mapping
     * @param string $tableName
     * @return MetadataDataObjectPDO
     */
    private function hydrateDataObject($data)
    {
        $dataObject = new MetadataDataObjectOracle(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $tableName 	= $data['TABLE_NAME'];
        $owner		= $data['OWNER'];
        $dataObject->setName($owner . '/' . $tableName);
        $columnDataObject = new MetaDataColumn();

        $statement = $this->pdo->prepare(sprintf("SELECT COLUMN_NAME as name, NULLABLE as  nullable, DATA_TYPE as type, DATA_LENGTH as length
												from SYS.ALL_TAB_COLUMNS
												where TABLE_NAME = '%s' and OWNER= '%s'", $tableName, $owner)
 // @TODO liste des champs
        );
        $statement->execute(array());
        $allFields = $statement->fetchAll(PDO::FETCH_ASSOC);
        $identifiers = $this->getIdentifiers($owner, $tableName);

        foreach ($allFields as $metadata) {
            $column = clone $columnDataObject;

            $type = $metadata['type'];

            if (isset($this->typeConversion[$type])) {
                $type = $this->typeConversion[$type];
            }

            $column->setName($metadata['name'])
                   ->setType($type)
                   ->setLength(
                       isset($metadata['length']) ?
                       $metadata['length'] :
                       null
                   );
            if (in_array($metadata['name'], $identifiers)) {
                $column->setPrimaryKey(true);
            }

            $dataObject->appendColumn($column);
        }

        return $dataObject;
    }

    /**
     * @param string $tableName
     * @return array
     */
    private function getIdentifiers($owner, $tableName)
    {
        $identifiers = array();

        $statement = $this->pdo->prepare(
            printf("select cc.COLUMN_NAME
		     from USER_CONSTRAINTS c
		     join ALL_CONS_COLUMNS cc 
            on c.CONSTRAINT_NAME = cc.CONSTRAINT_NAME 
            and  c.OWNER= cc.OWNER
		     where c.table_name like '%s'
		     and c.owner like '%s'
		     and CONSTRAINT_TYPE = '%s'", $owner, $tableName, "P")
        );
        $statement->execute(array($tableName));

        $constraintList = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($constraintList as $containt) {
            $identifiers[] = $containt['column_name'];
        }

        return $identifiers;
    }

    /**
     * Get particularie metadata from PDO
     *
     * @param string $tableName
     * @return \CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO
     */
    public function getMetadataFor($tableName, array $parentName = array())
    {
        return $this->hydrateDataObject($tableName);
    }
}
