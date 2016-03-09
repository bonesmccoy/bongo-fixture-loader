<?php

namespace Bones\Component\Fixture\Mongo\Data;

class DatabaseConfigurationReader
{
    public function __construct($config)
    {
        if (!isset($config['mongo_data_store'])) {
            throw new \InvalidArgumentException('Missing db configuration in config file.');
        }

        $config = $config['mongo_data_store'];
        if (empty($config['db_name'])) {
            throw new \InvalidArgumentException('Missing db name on configuration');
        }
        $this->databaseName = $config['db_name'];
        $this->host = isset($config['host']) ? $config['host'] : 'localhost';
        $this->port = isset($config['port']) ? $config['port'] : '27017';
        $this->username = isset($config['username']) ? $config['username'] : '';
        $this->password = isset($config['password']) ? $config['password'] : '';
        $this->connect = isset($config['connect']) ? $config['connect'] : '';
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
