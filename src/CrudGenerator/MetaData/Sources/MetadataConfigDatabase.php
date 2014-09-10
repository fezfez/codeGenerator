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
 * @author Stéphane Demonchaux
 */
abstract class MetadataConfigDatabase implements \JsonSerializable
{
    /**
     * Database Name
     *
     * @var string
     */
    protected $configDatabaseName = null;
    /**
     * Host
     *
     * @var string
     */
    protected $configHost = null;
    /**
     * User
     *
     * @var string
     */
    protected $configUser = null;
    /**
     * Password
     *
     * @var string
     */
    protected $configPassword = null;
    /**
     * Port
     *
     * @var string
     */
    protected $configPort = null;

    /**
     * Set database name
     * @param string $value
     * @return MetadataConfigDatabase
     */
    public function setConfigDatabaseName($value)
    {
        $this->configDatabaseName = $value;
        return $this;
    }
    /**
     * Set host
     * @param string $value
     * @return MetadataConfigDatabase
     */
    public function setConfigHost($value)
    {
        $this->configHost = $value;
        return $this;
    }
    /**
     * Set user
     * @param string $value
     * @return MetadataConfigDatabase
     */
    public function setConfigUser($value)
    {
        $this->configUser = $value;
        return $this;
    }
    /**
     * Set password
     * @param string $value
     * @return MetadataConfigDatabase
     */
    public function setConfigPassword($value)
    {
        $this->configPassword = $value;
        return $this;
    }
    /**
     * Set port
     * @param string $value
     * @return MetadataConfigDatabase
     */
    public function setConfigPort($value)
    {
        $this->configPort = $value;
        return $this;
    }

    /**
     * Get database name
     * @return string
     */
    public function getConfigDatabaseName()
    {
        return $this->configDatabaseName;
    }
    /**
     * Get host
     * @return string
     */
    public function getConfigHost()
    {
        return $this->configHost;
    }
    /**
     * Get user
     * @return string
     */
    public function getConfigUser()
    {
        return $this->configUser;
    }
    /**
     * Get password
     * @return string
     */
    public function getConfigPassword()
    {
        return $this->configPassword;
    }
    /**
     * Get port
     * @return string
     */
    public function getConfigPort()
    {
        return $this->configPort;
    }
    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfig::jsonSerialize()
    */
    public function jsonSerialize()
    {
        return array(
            'configDatabaseName' => $this->configDatabaseName,
            'configHost'         => $this->configHost,
            'configUser'         => $this->configUser,
            'configPassword'     => $this->configPassword,
            'configPort'         => $this->configPort
        );
    }
}
