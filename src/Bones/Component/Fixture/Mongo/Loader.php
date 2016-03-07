<?php


namespace Bones\Component\Mongo;


use Bones\Component\Fixture\AbstractFixtureLoader;
use Bones\Component\Fixture\Mongo\Data\DatabaseConfiguration;
use Bones\Component\Fixture\Mongo\Data\MongoDataStore;


class Loader extends AbstractFixtureLoader
{

    /**
     * @param $configurationFilePath
     */
    public function __construct($configurationFilePath)
    {
        parent::__construct($configurationFilePath);
        $this->dataStoreWriter = new MongoDataStore($this->config);

    }





    /**
     * @param string $fixtureDirectoryPath
     */
    public function addFixtureFromDirectory($fixtureDirectoryPath)
    {
        foreach (glob($fixtureDirectoryPath) as $file) {
            $fixture = $this->parser->parse(file_get_contents($file));
            $this->addFixture($fixture);
        }
    }

}
