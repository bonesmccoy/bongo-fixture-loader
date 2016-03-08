<?php


namespace Bones\Component\Fixture;


use Bones\Component\Fixture\Memory\Data\InMemoryDataStore;
use Bones\Component\Fixture\Mongo\Data\MongoDataStore;
use Symfony\Component\Yaml\Yaml;

class FixtureLoader implements LoaderInterface
{

    protected $parser;

    /**
     * @var array
     */
    protected $fixtures = array();

    protected $messages = array();

    /**
     * @var DataStoreInterface
     */
    protected $dataStoreWriter;

    private function __construct(DataStoreInterface $dataStoreWriter)
    {
        $this->parser = new Yaml();
        $this->dataStoreWriter = $dataStoreWriter;
    }

    public function addFixturesFromConfiguration($fixtureConfigurationFile, $applicationRoot = '')
    {
        $config = $this->parser->parse($fixtureConfigurationFile);

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
                "%s%s",
                ($absolute) ? ($applicationRoot . "/") : "",
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
        foreach (glob($fixtureDirectoryPath . "/*yml") as $file) {
            $this->addFixturesFromFile($file);
        }
    }


    /**
     * @param $fixtureFile
     */
    public function addFixturesFromFile($fixtureFile)
    {
        $fixture = $this->parser->parse(file_get_contents($fixtureFile));
        $this->addSingleFixture($fixture);
    }

    /**
     * @param $fixture
     */
    public function addSingleFixture($fixture)
    {
        $this->fixtures = array_merge($this->fixtures, $fixture);
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
                "Adding %s fixture to the collection %s\n",
                count($fixtures),
                $collection
            );

            $this->dataStoreWriter->emptyDataStore($collection);
            $this->dataStoreWriter->persist($collection, $fixtures);
        }
    }

    public static function factoryMongoFixtureLoader($configFile)
    {
        $dataStore = new MongoDataStore(Yaml::parse(file_get_contents($configFile)));

        return new FixtureLoader($dataStore);
    }

    public static function factoryInMemoryFixtureLoader()
    {
        $dataStore = new InMemoryDataStore();

        return new FixtureLoader($dataStore);
    }

}
