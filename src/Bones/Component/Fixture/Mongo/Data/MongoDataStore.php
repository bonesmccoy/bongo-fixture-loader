<?php

namespace Bones\Component\Fixture\Mongo\Data;

use Bones\Component\Fixture\DataStoreInterface;
use Bones\Component\Mongo\Connection;

class MongoDataStore implements DataStoreInterface
{
    /**
     * @var Connection
     */
    protected $databaseConfiguration;

    /**
     * @var \MongoClient
     */
    private $dataStoreWriter;

    public function __construct($config)
    {
        if (empty($config['mongo_data_store'])) {
            throw new \InvalidArgumentException('Missing mongo_data_store key in config');
        }

        $dataStoreConfiguration = $config['mongo_data_store'];
        $this->databaseConfiguration = Connection::createFromConfiguration($dataStoreConfiguration);
        $this->dataStoreWriter = new \MongoClient(
            $this->databaseConfiguration->getConnectionUrl(),
            $this->databaseConfiguration->getConnectionOptions()
        );
    }

    /**
     * @param $collection
     */
    public function emptyDataStore($collection)
    {
        $databaseName = $this->databaseConfiguration->getDatabaseName();
        $this->dataStoreWriter->$databaseName->$collection->remove(array());
    }

    /**
     * @param string $collection
     * @param array  $fixtures
     */
    public function persist($collection, $fixtures)
    {
        $databaseName = $this->databaseConfiguration->getDatabaseName();
        $this->dataStoreWriter->$databaseName->$collection->batchInsert($fixtures);
    }

    public function fetchCollection($collection)
    {
        $databaseName = $this->databaseConfiguration->getDatabaseName();
        $cursor = $this->dataStoreWriter->$databaseName->$collection->find();

        return iterator_to_array($cursor);
    }


}
