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

use CrudGenerator\MetaData\Config\AbstractConfig;
use CrudGenerator\MetaData\Config\ConfigException;

/**
 * PDO configuration for PDO Metadata adapter
 *
 * @author Stéphane Demonchaux
 */
class OracleConfig extends AbstractConfig
{
    /**
     * @var string Config definition
     */
    protected $definition = 'For use the Oracle adapter you need to define the database and how to get the PDO instance';
    /**
     * @var string Database Name
     */
    protected $databaseName = null;
    /**
     * @var string Host
     */
    protected $host = null;
    /**
     * @var string User
     */
    protected $user = null;
    /**
     * @var string Password
     */
    protected $password = null;
    /**
     * @var string Port
     */
    protected $port = null;

    /**
     * Set database name
     * @param string $value
     * @return \CrudGenerator\MetaData\Sources\PDO\PDOConfig
     */
    public function setDatabaseName($value)
    {
        $this->databaseName = $value;
        return $this;
    }
    /**
     * Set host
     * @param string $value
     * @return \CrudGenerator\MetaData\Sources\PDO\PDOConfig
     */
    public function setHost($value)
    {
        $this->host = $value;
        return $this;
    }
    /**
     * Set user
     * @param string $value
     * @return \CrudGenerator\MetaData\Sources\PDO\PDOConfig
     */
    public function setUser($value)
    {
        $this->user = $value;
        return $this;
    }
    /**
     * Set password
     * @param string $value
     * @return \CrudGenerator\MetaData\Sources\PDO\PDOConfig
     */
    public function setPassword($value)
    {
        $this->password = $value;
        return $this;
    }
    /**
     * Set port
     * @param string $value
     * @return \CrudGenerator\MetaData\Sources\PDO\PDOConfig
     */
    public function setPort($value)
    {
        $this->port = $value;
        return $this;
    }

    /**
     * Get database name
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }
    /**
     * Get host
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }
    /**
     * Get user
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Get password
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * Get port
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Test if its well configured
     * @throws ConfigException
     * @return \CrudGenerator\MetaData\Sources\PDO\PDOConfig
     */
    public function test()
    {
    	$db = '//' . $this->host . '/' . $this->databaseName; //e.g. '//192.168.1.1/orcl'
    	
    	try {
    		new PDO($db,$this->user,$this->password);
    		return $this;
    	} catch (\PDOException $e) {
    		throw new ConfigException('Connection failed with "' . $e->getMessage() . '"');
    	}
    	
    	//$conn = oci_connect($this->user, $this->password, $this->host . '/' . $this->databaseName);
    	/*if (!$conn) {
    		$e = oci_error();
    		throw new ConfigException('Connection failed with "' . $e->getMessage() . '"');
    	} else {
    		return $this;
    	}*/
    }
}
