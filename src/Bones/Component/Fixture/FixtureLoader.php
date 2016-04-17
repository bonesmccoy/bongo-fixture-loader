<?php

namespace Bones\Component\Fixture;

use Bones\Component\Fixture\Parser\FixtureTransformer;
use Bones\Component\Fixture\Memory\Data\InMemoryDataStore;
use Bones\Component\Fixture\Mongo\Data\MongoDataStore;
use Bones\Component\Fixture\Parser\FixtureTransformerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class FixtureLoader
 */
class FixtureLoader implements LoaderInterface
{
    const LOADER_MONGO = 'mongo';
    const LOADER_MEMORY = 'memory';

    protected $yamlParser;

    /**
     * @var array
     */
    protected $fixtures = array();

    protected $messages = array();

    /**
     * @var DataStoreInterface
     */
    protected $dataStoreWriter;

    /**
     * @var FixtureTransformerInterface
     */
    private $fixtureParser;

    /**
     * FixtureLoader constructor.
     * @param DataStoreInterface          $dataStoreWriter
     * @param FixtureTransformerInterface $fixtureParser
     */
    public function __construct(DataStoreInterface $dataStoreWriter, FixtureTransformerInterface $fixtureParser)
    {
        $this->yamlParser = new Yaml();
        $this->dataStoreWriter = $dataStoreWriter;
        $this->fixtureParser = $fixtureParser;
    }

    /**
     * @param string $configurationFilePath
     * @param string $applicationRoot
     */
    public function addFixturesFromConfiguration($configurationFilePath, $applicationRoot = '')
    {
        $config = $this->yamlParser->parse(file_get_contents($configurationFilePath));

        if (!isset($config['fixtures']['paths'])) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Fixture configuration not found in configuration file: %s\n DUMP: %s",
                    $configurationFilePath,
                    json_encode($config)
                )
            );
        }

        $absolute = (!empty($applicationRoot));
        foreach ($config['fixtures']['paths'] as $fixturesPath) {
            $fixtureDirectoryPath = sprintf(
                '%s%s',
                ($absolute) ? ($applicationRoot.'/') : '',
                $fixturesPath
            );
            $this->addFixturesFromDirectory($fixtureDirectoryPath);
        }
    }

    /**
     * @param string $fixtureDirectoryPath
     */
    public function addFixturesFromDirectory($fixtureDirectoryPath)
    {
        foreach (glob($fixtureDirectoryPath.'/*yml') as $file) {
            $this->addFixturesFromFile($file);
        }
    }

    /**
     * @param string $fixtureFilePath
     */
    public function addFixturesFromFile($fixtureFilePath)
    {
        $fixture = $this->yamlParser->parse(file_get_contents($fixtureFilePath));
        $this->addFixturesWithCollection($fixture);
    }

    /**
     * @param array $fixtures
     */
    public function addFixturesWithCollection(array $fixtures)
    {
        foreach ($fixtures as $collection => $fixture) {
            foreach ($fixture as $id => $fixtureRow) {
                $fixtures[$collection][$id] = $this->fixtureParser->parse($fixtureRow);
            }
        }
        $this->fixtures = array_merge($this->fixtures, $fixtures);
    }

    /**
     * @return array
     */
    public function getLoadedFixtures()
    {
        return $this->fixtures;
    }

    /**
     * Reset loaded Fixtures
     */
    public function resetLoadedFixtures()
    {
        $this->fixtures = array();
    }

    /**
     * Persist Loaded fixtures into the data store
     */
    public function persistLoadedFixtures()
    {
        $this->messages = array();
        foreach ($this->fixtures as $collection => $fixtures) {
            $this->messages[] = sprintf(
                'Adding %s fixture to the collection %s',
                count($fixtures),
                $collection
            );

            $this->dataStoreWriter->emptyDataStore($collection);
            $this->dataStoreWriter->persist($collection, $fixtures);
        }
    }

    /**
     * @return string
     */
    public function getMessagesAsString()
    {
        return implode(PHP_EOL, $this->messages);
    }

    /**
     * @param string $configurationFilePath
     *
     * @return FixtureLoader
     */
    public static function factoryMongoFixtureLoader($configurationFilePath)
    {
        $dataStore = new MongoDataStore(
            Yaml::parse(file_get_contents($configurationFilePath))
        );

        return new self($dataStore, new FixtureTransformer());
    }

    /**
     * @return FixtureLoader
     */
    public static function factoryInMemoryFixtureLoader()
    {
        $dataStore = new InMemoryDataStore();

        return new self($dataStore, new FixtureTransformer());
    }
}
