<?php

namespace tests\Bones\Component\Fixture\Mongo\Data;

use Bones\Component\Mongo\Connection;

/**
 * Class ConnectionTest
 */
class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * test configuration
     */
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

    /**
     * Test configuration with default value
     */
    public function testConfigurationWithDefaultValues()
    {
        $dbConfiguration = Connection::createFromConfiguration(array('db_name' => 'test-db'));

        $this->assertEquals(
            'mongodb://localhost:27017/test-db',
            $dbConfiguration->getConnectionUrl()
        );
    }

    /**
     * Test configuration with username and password
     */
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
