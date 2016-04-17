<?php

namespace Bones\Component\Fixture;

/**
 * Interface LoaderInterface
 *
 */
interface LoaderInterface
{
    /**
     * @param array $fixture
     */
    public function addFixturesWithCollection($fixture);

    /**
     * @param string $fixtureFilePath
     */
    public function addFixturesFromFile($fixtureFilePath);

    /**
     * @param string $fixtureDirectoryPath
     */
    public function addFixturesFromDirectory($fixtureDirectoryPath);

    /**
     * @param string $configurationFilePath
     */
    public function addFixturesFromConfiguration($configurationFilePath);

    /**
     * Persist Loaded fixtures into the data store
     */
    public function persistLoadedFixtures();

    /**
     * @return string
     */
    public function getMessagesAsString();
}
