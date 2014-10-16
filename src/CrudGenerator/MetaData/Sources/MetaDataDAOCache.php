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

use CrudGenerator\MetaData\Driver\DriverConfig;
/**
 * Metadata DAO cache
 *
 * @author Stéphane Demonchaux
 */
class MetaDataDAOCache implements MetaDataDAOInterface
{
    /**
     * @var MetaDataDAOInterface
     */
    private $metadataDAO = null;
    /**
     * @var array
     */
    private $directories = null;
    /**
     * @var DriverConfig
     */
    private $config = null;
    /**
     * @var boolean
     */
    private $noCache = null;

    /**
     * @param MetaDataDAOInterface $metadataDAO
     * @param array $directories
     * @param DriverConfig $config
     * @param boolean $noCache
     */
    public function __construct(
        MetaDataDAOInterface $metadataDAO,
        array $directories,
        DriverConfig $config = null,
        $noCache = false
    ) {
        $this->metadataDAO = $metadataDAO;
        $this->directories = $directories;
        $this->config      = $config;
        $this->noCache     = $noCache;
    }
    /**
     * Get all metadata from the concrete metadata DAO
     *
     * @return \CrudGenerator\MetaData\DataObject\MetaDataCollection
     */
    public function getAllMetadata()
    {
        $configName     = ($this->config !== null) ? $this->config->getUniqueName() : '';
        $cacheFilename  = $this->directories['Cache'] . DIRECTORY_SEPARATOR;
        $cacheFilename .= md5('all_metadata' . get_class($this->metadataDAO) . $configName);

        if (is_file($cacheFilename) === true && $this->noCache === false) {
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
        $configName     = ($this->config !== null) ? $this->config->getUniqueName() : '';
        $cacheFilename  = $this->directories['Cache'] . DIRECTORY_SEPARATOR;
        $cacheFilename .= md5('metadata' . $entityName . get_class($this->metadataDAO) . $configName);

        if (is_file($cacheFilename) === true && $this->noCache === false) {
            $data = unserialize(file_get_contents($cacheFilename));
        } else {
            $data = $this->metadataDAO->getMetadataFor($entityName, $parentName);
            file_put_contents($cacheFilename, serialize($data));
        }

        return $data;
    }
}
