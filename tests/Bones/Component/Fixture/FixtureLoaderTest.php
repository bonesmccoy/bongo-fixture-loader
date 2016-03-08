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
        $fixtureDirectory = "/tmp/test";
        $fixtureFilePath = $this->createYmlFile($fixtureDirectory, $fixtureFileContent);

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
        $fixtureFileContent = $this->getFixtureFileContent();
        $fixtureDirectory = "/tmp/test";
        $this->createYmlFile($fixtureDirectory, $fixtureFileContent);

        $this->loader->addFixturesFromDirectory($fixtureDirectory);

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
        $this->createYmlFile("/tmp/test", $this->getFixtureFileContent());

        $configDirectory = "/tmp/fixtures-config";

        $configYmlContent = <<<CFG
fixtures:
    paths:
        - /tmp/test

CFG;

        $configFile = $this->createYmlFile($configDirectory, $configYmlContent);

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
     */
    private function createYmlFile($containerDirectory, $ymlContent)
    {
        if (!is_dir($containerDirectory)) {
            mkdir($containerDirectory);
        }
        $fixtureFilePath = $containerDirectory . "/fixture.yml";
        $fh = fopen($fixtureFilePath, "w");
        fwrite($fh, $ymlContent);
        fclose($fh);
        return $fixtureFilePath;
    }

}
