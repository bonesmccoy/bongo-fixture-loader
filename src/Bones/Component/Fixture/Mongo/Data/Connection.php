<?php

namespace Bones\Component\Fixture\Mongo\Data;

class Connection
{
    private $databaseName;
    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $port;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    /**
     * @var bool
     */
    private $connect;

    public function __construct($databaseName, $host ='localhost', $port = '27017', $username = '', $password = '', $connect = true)
    {
        $this->databaseName = $databaseName;
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->connect = $connect;
    }

    public static function createFromConfiguration($config)
    {
        if (empty($config['db_name'])) {
            throw new \InvalidArgumentException('Missing db name on configuration');
        }

        $databaseName = $config['db_name'];
        $host = isset($config['host']) ? $config['host'] : 'localhost';
        $port = isset($config['port']) ? $config['port'] : '27017';
        $username = isset($config['username']) ? $config['username'] : '';
        $password = isset($config['password']) ? $config['password'] : '';
        $connect = isset($config['connect']) ? $config['connect'] : true;

        return new self($databaseName, $host, $port, $username, $password, $connect);
    }

    public function getConnectionUrl()
    {
        return sprintf('mongodb://%s%s%s%s/%s',
            ($this->username) ? "{$this->username}:" : '',
            ($this->password) ? "{$this->password}@" : '',
            $this->host,
            ":{$this->port}",
            $this->databaseName
        );
    }

    public function getConnectionOptions()
    {
        return array(
            'connect' => $this->connect,
        );
    }

    /**
     * @return mixed
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }
}
