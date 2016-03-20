<?php

namespace tests\Bones\Component\Fixture\Mongo\Data;

use Bones\Component\Mongo\Connection;

class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    public function testConfiguration()
    {
        $config = array(
            'host' => 'localhost',
            'port' => '27017',
            'username' => '',
            'password' => '',
            'db_name' => 'test-db',
            'connect' => true,
        );

        $dbConfiguration = Connection::createFromConfiguration($config);

        $this->assertInstanceof('\Bones\Component\Mongo\Connection', $dbConfiguration);
    }

    public function testConfigurationWithDefaultValues()
    {
        $dbConfiguration = Connection::createFromConfiguration(array('db_name' => 'test-db'));

        $this->assertEquals(
            'mongodb://localhost:27017/test-db',
            $dbConfiguration->getConnectionUrl()
        );
    }

    public function testConfigurationWithUsernameAndPassword()
    {
        $config = array(
            'host' => 'localhost',
            'port' => '27017',
            'username' => 'username',
            'password' => 'password',
            'db_name' => 'test-db',
            'connect' => true,
        );

        $dbConfiguration = Connection::createFromConfiguration($config);

        $this->assertEquals(
            'mongodb://username:password@localhost:27017/test-db',
            $dbConfiguration->getConnectionUrl()
        );
    }
}
