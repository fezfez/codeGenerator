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

use CrudGenerator\MetaData\Sources\MetaDataDAOInterface;
use CrudGenerator\MetaData\DataObject\MetaDataCollection;
use CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAO;

/**
 * Xml adapter
 */
class XmlMetaDataDAO implements MetaDataDAOInterface
{
    /**
     * @var JsonMetaDataDAO
     */
    private $jsonMetaDataDAO = null;

    /**
     * Constructor.
     * @param JsonMetaDataDAO $jsonMetaDataDAO
     */
    public function __construct(JsonMetaDataDAO $jsonMetaDataDAO)
    {
        $this->jsonMetaDataDAO = $jsonMetaDataDAO;
    }

    /**
     * Get all metadata from MySQL
     *
     * @return MetaDataCollection
     */
    public function getAllMetadata()
    {
        return $this->jsonMetaDataDAO->getAllMetadata();
    }

    /**
     * Get particularie metadata from MySQL
     *
     * @param string $tableName
     * @return \CrudGenerator\MetaData\Sources\MySQL\MetadataDataObjectMySQL
     */
    public function getMetadataFor($tableName, array $parentName = array())
    {
        return $this->jsonMetaDataDAO->getMetadataFor($tableName);
    }
}
