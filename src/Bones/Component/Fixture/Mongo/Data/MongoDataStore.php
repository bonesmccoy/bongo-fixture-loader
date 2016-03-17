<?php

namespace Bones\Component\Fixture\Mongo\Data;

use Bones\Component\Fixture\DataStoreInterface;
use Bones\Component\Fixture\Parser\FixtureParserInterface;
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
    /**
     * @var FixtureParserInterface
     */
    private $fixtureParser;

    public function __construct($config, FixtureParserInterface $fixtureParser)
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

        $this->fixtureParser = $fixtureParser;
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
        $fixtures = $this->applyParsing($fixtures);
        $this->dataStoreWriter->$databaseName->$collection->batchInsert($fixtures);
    }

    /**
     * @param $fixtures
     * @return mixed
     */
    public function applyParsing($fixtures)
    {
        foreach ($fixtures as $id => $fixture) {
            $fixtures[$id] = $this->fixtureParser->parse($fixture);
        }
        return $fixtures;
    }
}
