<?php


namespace tests\Bones\Component\Fixture;


use Bones\Component\Fixture\FixtureLoader;

class FixtureLoaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FixtureLoader
     */
    protected $loader;

    public function setUp()
    {
        $this->loader = FixtureLoader::factoryInMemoryFixtureLoader();
    }


    public function testLoadSingleFixture()
    {
        $fixture = array('collection' => array(
            array("id" => 1, "name" => "fixture 1"),
            array("id" => 2, "name" => "fixture 2"),
            array("id" => 3, "name" => "fixture 3"),
            array("id" => 4, "name" => "fixture 4"),

            )
        );

        $this->loader->addSingleFixture($fixture);
        $loadedFixtures = $this->loader->getLoadedFixtures();
        $this->assertCount(
            1,
            $loadedFixtures
        );

        $this->assertArrayHasKey('collection', $loadedFixtures);
        foreach($loadedFixtures as $collectionName => $fixturesInCollection) {
            foreach($fixturesInCollection as $f) {
                $this->assertTrue(is_array($f));
                $this->assertArrayHasKey('id', $f);
                $this->assertArrayHasKey('name', $f);
            }
        }
    }

    public function testAddFixturesFromFile()
    {
        $fixtureFileContent = $this->getFixtureFileContent();
        $fixtureFilePath = $this->createYmlFile("fixtures", $fixtureFileContent);

        $this->loader->addFixturesFromFile($fixtureFilePath);

        $loadedFixtures = $this->loader->getLoadedFixtures();
        $this->assertCount(
            2,
            $loadedFixtures
        );

        $this->assertArrayHasKey('first_collection', $loadedFixtures);
        $this->assertArrayHasKey('second_collection', $loadedFixtures);
    }

    public function testAddFixturesFromDirectory()
    {
        $this->createYmlFile("fixtures", $this->getFixtureFileContent());

        $this->loader->addFixturesFromDirectory($this->getTemporaryDirectory() . "/fixtures");

        $loadedFixtures = $this->loader->getLoadedFixtures();
        $this->assertCount(
            2,
            $loadedFixtures
        );

        $this->assertArrayHasKey('first_collection', $loadedFixtures);
        $this->assertArrayHasKey('second_collection', $loadedFixtures);
    }

    public function testAddFixturesFromConfigurationFile()
    {
        $this->createYmlFile("fixtures", $this->getFixtureFileContent());

        $temporaryDirectory = $this->getTemporaryDirectory();

        $configYmlContent = <<<CFG
fixtures:
    paths:
        - {$temporaryDirectory}/test

CFG;

        $configFile = $this->createYmlFile("config", $configYmlContent);

        $this->loader->addFixturesFromConfiguration($configFile);

        $loadedFixtures = $this->loader->getLoadedFixtures();
        $this->assertCount(
            2,
            $loadedFixtures
        );

        $this->assertArrayHasKey('first_collection', $loadedFixtures);
        $this->assertArrayHasKey('second_collection', $loadedFixtures);
    }


    /**
     * @return string
     */
    private function getFixtureFileContent()
    {
        $fixtureFileContent = <<<YML
first_collection:
    - { "id" : 1, "name" : "fixture 1"}
    - { "id" : 2, "name" : "fixture 2"}
    - { "id" : 3, "name" : "fixture 3"}
    - { "id" : 4, "name" : "fixture 4"}
    - { "id" : 5, "name" : "fixture 5"}

second_collection:
    - { "id" : 1, "name" : "fixture 1"}
    - { "id" : 2, "name" : "fixture 2"}
    - { "id" : 3, "name" : "fixture 3"}
    - { "id" : 4, "name" : "fixture 4"}
    - { "id" : 5, "name" : "fixture 5"}
YML;
        return $fixtureFileContent;
    }

    /**
     * @param $containerDirectory
     * @param $ymlContent
     * @return string
     * @throws \Exception
     */
    private function createYmlFile($containerDirectory, $ymlContent)
    {
        $containerDirectory = $this->getTemporaryDirectory() . "/" . $containerDirectory;

        if (!is_dir($containerDirectory)) {
            @mkdir($containerDirectory);
            if (!is_dir($containerDirectory)) {
                throw new \Exception("unable to write in $containerDirectory");
            }
        }
        $fixtureFilePath = $containerDirectory . "/bongo.yml";
        $fh = fopen($fixtureFilePath, "w");
        fwrite($fh, $ymlContent);
        fclose($fh);
        return $fixtureFilePath;
    }

    /**
     * @return string
     */
    private function getTemporaryDirectory()
    {
        $rootFixtureDir = getEnv('TEMP_FIXTURE_DIR');
        return $rootFixtureDir;
    }

}
