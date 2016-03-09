<?php

namespace tests\Bones\Component\Fixture\Mongo\Data;

use Bones\Component\Fixture\Mongo\Data\DatabaseConfigurationReader;

class DatabaseConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testConfiguration()
    {
        $fullDataStore = array(
            'host' => 'localhost',
            'port' => '27017',
            'username' => '',
            'password' => '',
            'db_name' => 'test-db',
            'connect' => true,
        );

        $config = array('mongo_data_store' => $fullDataStore);

        $dbConfiguration = new DatabaseConfigurationReader($config);

        $this->assertInstanceof('\Bones\Component\Fixture\Mongo\Data\DatabaseConfigurationReader', $dbConfiguration);
    }

    public function testConfigurationWithDefaultValues()
    {
        $config = array('mongo_data_store' => array('db_name' => 'test-db'));

        $dbConfiguration = new DatabaseConfigurationReader($config);

        $this->assertEquals(
            'mongodb://localhost:27017/test-db',
            $dbConfiguration->getConnectionUrl()
        );
    }

    public function testConfigurationWithUsernameAndPassword()
    {
        $fullDataStore = array(
            'host' => 'localhost',
            'port' => '27017',
            'username' => 'username',
            'password' => 'password',
            'db_name' => 'test-db',
            'connect' => true,
        );

        $config = array('mongo_data_store' => $fullDataStore);

        $dbConfiguration = new DatabaseConfigurationReader($config);

        $this->assertEquals(
            'mongodb://username:password@localhost:27017/test-db',
            $dbConfiguration->getConnectionUrl()
        );
    }
}
