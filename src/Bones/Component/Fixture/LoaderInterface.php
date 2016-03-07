<?php

namespace Bones\Component\Fixture;


interface LoaderInterface
{
    /**
     * @param $fixture
     */
    public function addFixture($fixture);

    /**
     * @param string $fixtureDirectoryPath
     */
    public function addFixtureFromDirectory($fixtureDirectoryPath);

    /**
     * @param $collection
     */
    public function emptyDataStore($collection);

    /**
     * @param $collection
     * @param $fixtures
     */
    public function dataStoreBatchInsert($collection, $fixtures);
}
