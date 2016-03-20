<?php

namespace Bones\Component\Fixture;

use Bones\Component\Fixture\Parser\FixtureTransformer;
use Bones\Component\Fixture\Memory\Data\InMemoryDataStore;
use Bones\Component\Fixture\Mongo\Data\MongoDataStore;
use Bones\Component\Fixture\Parser\FixtureTransformerIntreface;
use Symfony\Component\Yaml\Yaml;

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
     * @var FixtureTransformerIntreface
     */
    private $fixtureParser;

    /**
     * FixtureLoader constructor.
     * @param DataStoreInterface $dataStoreWriter
     * @param FixtureTransformerIntreface $fixtureParser
     */
    public function __construct(DataStoreInterface $dataStoreWriter, FixtureTransformerIntreface $fixtureParser)
    {
        $this->yamlParser = new Yaml();
        $this->dataStoreWriter = $dataStoreWriter;
        $this->fixtureParser = $fixtureParser;
    }

    public function addFixturesFromConfiguration($fixtureConfigurationFile, $applicationRoot = '')
    {
        $config = $this->yamlParser->parse(file_get_contents($fixtureConfigurationFile));

        if (!isset($config['fixtures']['paths'])) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Fixture configuration not found in configuration file: %s\n DUMP: %s",
                    $fixtureConfigurationFile,
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
     * @param $fixtureFile
     */
    public function addFixturesFromFile($fixtureFile)
    {
        $fixture = $this->yamlParser->parse(file_get_contents($fixtureFile));
        $this->addFixturesWithCollection($fixture);
    }

    /**
     * @param $fixtures
     */
    public function addFixturesWithCollection($fixtures)
    {
        foreach($fixtures as $collection => $fixture) {
            foreach($fixture as $id => $fixtureRow) {
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

    public function resetLoadedFixtures()
    {
        $this->fixtures = array();
    }

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

    public function getMessagesAsString()
    {
        return implode(PHP_EOL, $this->messages);
    }

    /**
     * @param $configFile
     *
     * @return FixtureLoader
     */
    public static function factoryMongoFixtureLoader($configFile)
    {
        $dataStore = new MongoDataStore(
            Yaml::parse(file_get_contents($configFile))
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
