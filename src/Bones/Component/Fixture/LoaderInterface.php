<?php

namespace Bones\Component\Fixture;


interface LoaderInterface
{

    /**
     * @param $fixture
     */
    public function addSingleFixture($fixture);

    /**
     * @param $fixtureFile
     */
    public function addFixturesFromFile($fixtureFile);
    
    /**
     * @param string $fixtureDirectoryPath
     */
    public function addFixturesFromDirectory($fixtureDirectoryPath);


    public function addFixturesFromConfiguration($fixtureConfigurationFile);


    public function persistLoadedFixtures();


    public function dumpMessages();

}
