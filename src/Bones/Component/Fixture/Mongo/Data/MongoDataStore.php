<?php


namespace Bones\Component\Fixture\Mongo\Data;


use Bones\Component\Fixture\DataStoreInterface;

class MongoDataStore implements DataStoreInterface
{
    /**
     * @var DatabaseConfiguration
     */
    protected $databaseConfiguration;

    /**
     * @var \MongoClient
     */
    private $dataStoreWriter;


    public function __construct($dataStoreConfiguration)
    {
        $this->databaseConfiguration = new DatabaseConfiguration($dataStoreConfiguration);
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
     * @param array $fixtures
     */
    public function persist($collection, $fixtures)
    {
        $databaseName = $this->databaseConfiguration->getDatabaseName();
        $this->dataStoreWriter->$databaseName->$collection->batchInsert($fixtures);
    }
}
