<?php


namespace Bones\Component\Fixture\Mongo\Data;


class DataStoreTest extends \PHPUnit_Framework_TestCase
{

    public function testCreationWithCorrectConfiguration()
    {
        $config = array(
            'mongo_data_store' =>  array(
                'host' => 'localhost',
                'port' => '27017',
                'username' => '',
                'password' => '',
                'db_name' => 'test-db',
                'connect' => true,
            )
        );

        $dataStore = new MongoDataStore($config);

        $this->assertInstanceOf('\Bones\Component\Fixture\Mongo\Data\MongoDataStore', $dataStore);
    }
}
