<?php

namespace tests\Bones\Component\Fixture\Mongo\Data;

use Bones\Component\Fixture\Mongo\Data\MongoDataStore;
use Bones\Component\Fixture\Parser\FixtureTransformer;
use Bones\Component\Mongo\Connection;

class DataStoreTest extends \PHPUnit_Framework_TestCase
{
    protected $databaseParameters = array(
        'host' => 'localhost',
        'port' => '27017',
        'username' => '',
        'password' => '',
        'db_name' => 'test-db',
        'connect' => true,
    );
    protected $dataStoreParameters;
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
    private $collection = 'collection-test';

    public function setUp()
    {


    }

    public function tearDown()
    {
        if ($this->collection and ($this->dataStore instanceof MongoDataStore)) {
            $this->dataStore->emptyDataStore($this->collection);
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongDatabaseConfiguration()
    {
        $datastore = new MongoDataStore(array());
    }

    public function testCreationWithCorrectConfiguration()
    {
        $this->createMongoDataStore();
        $this->assertInstanceOf('\Bones\Component\Fixture\Mongo\Data\MongoDataStore', $this->dataStore);
    }

    public function testPersist()
    {
        $this->createMongoDataStore();

        $fixture = array(
            '_id' => '<id@56eb45003639330941000001>',
            'name' => 'ted',
            'referencedId' => '<id@56eb45003639330941000001>',
        );

        $fixtureParser = new FixtureTransformer();
        $fixture = $fixtureParser->parse($fixture);

        $databaseName = $this->connection->getDatabaseName();

        $this->dataStore->persist(
            $this->collection,
            array($fixture)
        );

        foreach ($this->client->$databaseName->{$this->collection}->find() as $document) {
            $this->assertInstanceOf(
                '\MongoId', $document['_id']
            );

            $this->assertInstanceOf(
                '\MongoId',
                $document['referencedId']
            );
        }
    }

    public function createMongoDataStore()
    {
        $this->dataStoreParameters = array(
            'mongo_data_store' => $this->databaseParameters,
        );

        $this->connection = Connection::createFromConfiguration($this->databaseParameters);
        $this->client = new \MongoClient($this->connection->getConnectionUrl(), $this->connection->getConnectionOptions());
        $this->dataStore = new MongoDataStore($this->dataStoreParameters);

        $this->dataStore->emptyDataStore($this->collection);
    }
}
