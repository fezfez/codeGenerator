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
namespace CrudGenerator\MetaData\Sources;

/**
 * Metadata DAO cache
 *
 * @author Stéphane Demonchaux
 */
class MetaDataDAOCache implements MetaDataDAOInterface
{
    /**
     * @var MetaDataDAO
     */
    private $metadataDAO = null;
    /**
     * @var array
     */
    private $directories = null;
    /**
     * @var boolean
     */
    private $noCache = null;

    /**
     * @param MetaDataDAO $metadataDAO
     * @param array $directories
     * @param boolean $noCache
     */
    public function __construct(MetaDataDAO $metadataDAO, array $directories, $noCache = false)
    {
        $this->metadataDAO     = $metadataDAO;
        $this->directories     = $directories;
        $this->noCache = $noCache;
    }
    /**
     * Get all metadata from the concrete metadata DAO
     *
     * @return \CrudGenerator\MetaData\DataObject\MetaDataCollection
     */
    public function getAllMetadata()
    {
        $cacheFilename = $this->directories['Cache'] . DIRECTORY_SEPARATOR . md5('all_metadata' . get_class($this->metadataDAO));

        if (is_file($cacheFilename) && $this->noCache === false) {
            $data = unserialize(file_get_contents($cacheFilename));
        } else {
            $data = $this->metadataDAO->getAllMetadata();
            file_put_contents($cacheFilename, serialize($data));
        }

        return $data;
    }

    /**
     * Get particularie metadata from the concrete metadata DAO
     *
     * @param string $entityName
     * @return \CrudGenerator\MetaData\DataObject\MetaData
     */
    public function getMetadataFor($entityName, array $parentName = array())
    {
        $cacheFilename = $this->directories['Cache'] . DIRECTORY_SEPARATOR . md5('metadata' . $entityName . get_class($this->metadataDAO));

        if (is_file($cacheFilename) && $this->noCache === false) {
            $data = unserialize(file_get_contents($cacheFilename));
        } else {
            $data = $this->metadataDAO->getMetadataFor($entityName, $parentName);
            file_put_contents($cacheFilename, serialize($data));
        }

        return $data;
    }
}
