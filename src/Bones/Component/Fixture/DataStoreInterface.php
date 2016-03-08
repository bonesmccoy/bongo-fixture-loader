<?php


namespace Bones\Component\Fixture;


interface DataStoreInterface
{

    /**
     * @param $collection
     */
    public function emptyDataStore($collection);

    /**
     * @param string $collection
     * @param array $fixtures
     * @return
     */
    public function persist($collection, $fixtures);
}
