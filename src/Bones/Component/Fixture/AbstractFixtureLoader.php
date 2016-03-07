<?php


namespace Bones\Component\Fixture;


use Symfony\Component\Yaml\Yaml;

abstract class AbstractFixtureLoader implements LoaderInterface
{

    protected $parser;

    protected $config;

    /**
     * @var array
     */
    protected $fixtures = array();

    protected $messages = array();

    /**
     * @var DataStoreInterface
     */
    protected $dataStoreWriter;

    public function __construct($configurationFilePath)
    {
        $this->parser = new Yaml();
        if (!file_exists($configurationFilePath)) {
            throw new \InvalidArgumentException("Configuration file $configurationFilePath doesn't exists");
        }

        $this->config = $this->parser->parse($configurationFilePath);
    }

    public function parseFixturesDirectories($applicationRoot)
    {
        if (!isset($this->config['fixtures']['paths'])) {
            throw new \InvalidArgumentException("Fixture configuration not found in configuration file");
        }

        foreach ($this->config['fixtures']['paths'] as $fixturesPath) {
            $fixtureDirectoryPath = $applicationRoot . "/" . $fixturesPath . "/*yml";
            $this->addFixtureFromDirectory($fixtureDirectoryPath);
        }

    }

    /**
     * @param $fixture
     */
    public function addFixture($fixture)
    {
        $this->fixtures = array_merge($this->fixtures, $fixture);
    }


    public function run()
    {
        $this->writeFixtures();
    }

    private function writeFixtures()
    {
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

    public function dumpMessages()
    {
        foreach ($this->messages as $message) {
            echo $message . "\n";
        }
    }
}
