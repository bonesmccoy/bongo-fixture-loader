<?php

namespace tests\Bones\Component\Fixture\Mongo\Data;

use Bones\Component\Fixture\Mongo\Data\MongoDataStore;
use Bones\Component\Fixture\Mongo\FixtureParser;
use Bones\Component\Mongo\Connection;

class DataStoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MongoDataStore
     */
    private $dataStore;

    /**
     * @var \MongoClient
     */
    private $client;

    /**
     * @var Connection
     */
    private $connection;

    /** @var  string */
    private $collection;

    public function setUp()
    {
        $mongoConfig = array(
            'host' => 'localhost',
            'port' => '27017',
            'username' => '',
            'password' => '',
            'db_name' => 'test-db',
            'connect' => true,
        );

        $mongoDataStore = array(
            'mongo_data_store' => $mongoConfig,
        );

        $this->connection = Connection::createFromConfiguration($mongoConfig);
        $this->client = new \MongoClient($this->connection->getConnectionUrl(), $this->connection->getConnectionOptions());
        $this->collection = 'collection-test';
        $this->dataStore = new MongoDataStore($mongoDataStore, new FixtureParser());

        $this->dataStore->emptyDataStore($this->collection);
    }

    public function testCreationWithCorrectConfiguration()
    {
        $this->assertInstanceOf('\Bones\Component\Fixture\Mongo\Data\MongoDataStore', $this->dataStore);
    }

    public function testPersist()
    {
        $fixture = array(
            '_id' => 1,
            'name' => 'ted',
            'referencedId' => 'ref:1',
        );

        $collection = $this->collection;
        $databaseName = $this->connection->getDatabaseName();

        $this->dataStore->persist($collection, array($fixture));

        foreach ($this->client->$databaseName->$collection->find() as $document) {
            $this->assertInstanceOf(
                '\MongoId', $document['_id']
            );

            $this->assertInstanceOf(
                '\MongoId',
                $document['referencedId']
            );
        }
    }
}
