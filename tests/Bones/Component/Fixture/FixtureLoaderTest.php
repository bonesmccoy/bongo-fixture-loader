<?php

namespace tests\Bones\Component\Fixture;

use Bones\Component\Fixture\FixtureLoader;
use Bones\Component\Fixture\Memory\Data\InMemoryDataStore;
use Bones\Component\Fixture\Mongo\Data\MongoDataStore;
use Bones\Component\Fixture\Parser\FixtureTransformer;
use Bones\Component\Mongo\Connection;
use Symfony\Component\Yaml\Yaml;

class FixtureLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FixtureLoader
     */
    protected $inMemoryFixtureLoader;
    /**
     * @var FixtureLoader
     */
    protected $mongoFixtureLoader;
    protected $connection;
    protected $client;
    protected $mongoDataStore;
    protected $mongoConfig;

    public function setUp()
    {
        $this->inMemoryFixtureLoader = FixtureLoader::factoryInMemoryFixtureLoader();

        $this->mongoConfig = Yaml::parse(file_get_contents(__DIR__ . "/test-config.yml" ));

        $this->getMongoClient();

        $this->mongoDataStore = new MongoDataStore($this->mongoConfig);

        $this->mongoFixtureLoader = new FixtureLoader($this->mongoDataStore, new FixtureTransformer());

    }

    public function testLoadSingleFixture()
    {
        $fixture = array('collection' => array(
            array('_id' => 1, 'name' => 'fixture 1'),
            array('_id' => 2, 'name' => 'fixture 2'),
            array('_id' => 3, 'name' => 'fixture 3'),
            array('_id' => 4, 'name' => 'fixture 4'),

            ),
        );

        $this->inMemoryFixtureLoader->addFixturesWithCollection($fixture);
        $loadedFixtures = $this->inMemoryFixtureLoader->getLoadedFixtures();
        $this->assertCount(
            1,
            $loadedFixtures
        );

        $this->assertArrayHasKey('collection', $loadedFixtures);
        foreach ($loadedFixtures as $collectionName => $fixturesInCollection) {
            foreach ($fixturesInCollection as $f) {
                $this->assertTrue(is_array($f));
                $this->assertArrayHasKey('_id', $f);
                $this->assertArrayHasKey('name', $f);
            }
        }
    }

    public function testLoadFixtureAndUnload()
    {
        $fixture = array('collection' => array(
            array('_id' => 1, 'name' => 'fixture 1'),
            array('_id' => 2, 'name' => 'fixture 2'),
            array('_id' => 3, 'name' => 'fixture 3'),
            array('_id' => 4, 'name' => 'fixture 4'),

            ),
        );

        $this->inMemoryFixtureLoader->addFixturesWithCollection($fixture);
        $this->inMemoryFixtureLoader->resetLoadedFixtures();
        $this->assertCount(
            0,
            $this->inMemoryFixtureLoader->getLoadedFixtures()
        );
    }

    public function testAddFixturesFromFile()
    {
        $fixtureFileContent = $this->getFixtureFileContent();
        $fixtureFilePath = $this->createYmlFile('fixtures', $fixtureFileContent);

        $this->inMemoryFixtureLoader->addFixturesFromFile($fixtureFilePath);

        $loadedFixtures = $this->inMemoryFixtureLoader->getLoadedFixtures();
        $this->assertCount(
            2,
            $loadedFixtures
        );

        $this->assertArrayHasKey('first_collection', $loadedFixtures);
        $this->assertArrayHasKey('second_collection', $loadedFixtures);
    }

    public function testAddFixturesFromDirectory()
    {
        $this->createYmlFile('fixtures', $this->getFixtureFileContent());

        $this->inMemoryFixtureLoader->addFixturesFromDirectory($this->getTemporaryDirectory().'/fixtures');

        $loadedFixtures = $this->inMemoryFixtureLoader->getLoadedFixtures();
        $this->assertCount(
            2,
            $loadedFixtures
        );

        $this->assertArrayHasKey('first_collection', $loadedFixtures);
        $this->assertArrayHasKey('second_collection', $loadedFixtures);
    }

    public function testAddFixturesFromConfigurationFile()
    {
        $this->createYmlFile('fixtures', $this->getFixtureFileContent());

        $temporaryDirectory = $this->getTemporaryDirectory();

        $configYmlContent = <<<CFG
fixtures:
    paths:
        - {$temporaryDirectory}/fixtures

CFG;

        $configFile = $this->createYmlFile('config', $configYmlContent);

        $this->inMemoryFixtureLoader->addFixturesFromConfiguration($configFile);

        $loadedFixtures = $this->inMemoryFixtureLoader->getLoadedFixtures();
        $this->assertCount(
            2,
            $loadedFixtures
        );
        $this->assertArrayHasKey('first_collection', $loadedFixtures);
        $this->assertArrayHasKey('second_collection', $loadedFixtures);

        $this->inMemoryFixtureLoader->persistLoadedFixtures();

        $messageString = $this->inMemoryFixtureLoader->getMessagesAsString();

        $this->assertTrue(
            is_string($messageString)
        );

        $messages = explode(PHP_EOL, $messageString);
        $this->assertCount(2, $messages);

        $this->assertEquals(
            'Adding 5 fixture to the collection first_collection',
            $messages[0]
        );
        $this->assertEquals(
            'Adding 5 fixture to the collection second_collection',
            $messages[1]
        );

    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadingWrongConfigurationFile()
    {
        $this->createYmlFile('fixtures', $this->getFixtureFileContent());

        $configYmlContent = <<<CFG
fixtures:

CFG;

        $configFile = $this->createYmlFile('config', $configYmlContent);
        $this->inMemoryFixtureLoader->addFixturesFromConfiguration($configFile);

        $configYmlContent = "";
        $configFile = $this->createYmlFile('config', $configYmlContent);
        $this->inMemoryFixtureLoader->addFixturesFromConfiguration($configFile);

    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadingEmptyConfiguratonFile()
    {
        $configYmlContent = "";
        $configFile = $this->createYmlFile('config', $configYmlContent);
        $this->inMemoryFixtureLoader->addFixturesFromConfiguration($configFile);
    }

    /**
     * @return string
     */
    private function getFixtureFileContent()
    {
        $fixtureFileContent = <<<YML
first_collection:
    - { "_id" : 1, "name" : "fixture 1"}
    - { "_id" : 2, "name" : "fixture 2"}
    - { "_id" : 3, "name" : "fixture 3"}
    - { "_id" : 4, "name" : "fixture 4"}
    - { "_id" : 5, "name" : "fixture 5"}

second_collection:
    - { "_id" : 1, "name" : "fixture 1"}
    - { "_id" : 2, "name" : "fixture 2"}
    - { "_id" : 3, "name" : "fixture 3"}
    - { "_id" : 4, "name" : "fixture 4"}
    - { "_id" : 5, "name" : "fixture 5"}
YML;

        return $fixtureFileContent;
    }

    /**
     * @param $containerDirectory
     * @param $ymlContent
     *
     * @return string
     *
     * @throws \Exception
     */
    private function createYmlFile($containerDirectory, $ymlContent)
    {
        $containerDirectory = $this->getTemporaryDirectory().'/'.$containerDirectory;

        if (!is_dir($containerDirectory)) {
            @mkdir($containerDirectory);
            if (!is_dir($containerDirectory)) {
                throw new \Exception("unable to write in $containerDirectory");
            }
        }
        $fixtureFilePath = $containerDirectory.'/bongo.yml';
        $fh = fopen($fixtureFilePath, 'w');
        fwrite($fh, $ymlContent);
        fclose($fh);

        return $fixtureFilePath;
    }

    public function testLoaderFromConfiguration()
    {
        $loader = FixtureLoader::factoryMongoFixtureLoader(
            __DIR__ . '/test-config.yml'
        );


    }

    public function testLoaderFromConfigurationAndAddFixture()
    {
        $loader = FixtureLoader::factoryMongoFixtureLoader(
            __DIR__ . '/test-config.yml'
        );

        $loader->addFixturesWithCollection(
            array('collection' => array(array('_id' => 1, 'name' => 'brian')),
            )
        );

        $loader->persistLoadedFixtures();


    }

    /**
     * @return string
     */
    private function getTemporaryDirectory()
    {
        $rootFixtureDir = getEnv('TEMP_FIXTURE_DIR');

        return $rootFixtureDir;
    }

    public function getMongoClient()
    {
        if (!($this->client instanceof \MongoClient)) {
            $this->connection = Connection::createFromConfiguration($this->mongoConfig['mongo_data_store']);
            $this->client = new \MongoClient($this->connection->getConnectionUrl(), $this->connection->getConnectionOptions());
        }
    }
}
