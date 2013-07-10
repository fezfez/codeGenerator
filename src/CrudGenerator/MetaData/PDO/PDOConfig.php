<?php
namespace CrudGenerator\MetaData\PDO;

use CrudGenerator\MetaData\AbstractConfig;
use CrudGenerator\MetaData\Config\ConfigException;
use Symfony\Component\Console\Output\OutputInterface;

class PDOConfig extends AbstractConfig
{
    /**
     * @var string Config definition
     */
    protected $definition = 'For use the PDO adapter you need to define the database and how to get the PDO instance';
    /**
     * @var string
     */
    protected $databaseName = null;
    /**
     * @var string
     */
    protected $host = null;
    /**
     * @var string
     */
    protected $user = null;
    /**
     * @var string
     */
    protected $password = null;
    /**
     * @var string
     */
    protected $port = null;
    /**
     * @var string
     */
    protected $type = null;

    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\PDO\PDOConfig
     */
    public function setDatabaseName($value)
    {
        $this->databaseName = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\PDO\PDOConfig
     */
    public function setHost($value)
    {
        $this->host = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\PDO\PDOConfig
     */
    public function setUser($value)
    {
        $this->user = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\PDO\PDOConfig
     */
    public function setPassword($value)
    {
        $this->password = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\PDO\PDOConfig
     */
    public function setPort($value)
    {
        $this->port = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\PDO\PDOConfig
     */
    public function setType($value)
    {
        $this->type = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }
    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }
    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param OutputInterface $output
     * @throws ConfigException
     * @return \CrudGenerator\MetaData\PDO\PDOConfig
     */
    public function test(OutputInterface $output)
    {
        try {
            $pdo = new \PDO($this->type . ':dbname='.$this->databaseName . ';host='.$this->host, $this->user, $this->password);
            $output->writeLn('Connection work !');
            return $this;
        } catch (\PDOException $e) {
            throw new ConfigException('<error>Connection failed with "' . $e->getMessage() . '"</error>');
        }
    }
}
