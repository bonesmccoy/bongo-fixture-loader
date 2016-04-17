<?php

namespace Bones\Component\Fixture\Mongo\Data;

use Bones\Component\Fixture\DataStoreInterface;
use Bones\Component\Mongo\Connection;

/**
 * Class MongoDataStore
 * @package Bones\Component\Fixture\Mongo\Data
 */
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

    /**
     * MongoDataStore constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
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
     * {@inheritdoc}
     */
    public function emptyDataStore($collectionName)
    {
        $databaseName = $this->databaseConfiguration->getDatabaseName();
        $this->dataStoreWriter->$databaseName->$collectionName->remove(array());
    }

    /**
     * {@inheritdoc}
     */
    public function persist($collectionName, $fixtures)
    {
        $databaseName = $this->databaseConfiguration->getDatabaseName();
        $this->dataStoreWriter->$databaseName->$collectionName->batchInsert($fixtures);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchCollection($collectionName)
    {
        $databaseName = $this->databaseConfiguration->getDatabaseName();
        $cursor = $this->dataStoreWriter->$databaseName->$collectionName->find();

        return iterator_to_array($cursor);
    }
}
