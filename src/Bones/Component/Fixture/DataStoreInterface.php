<?php

namespace Bones\Component\Fixture;

/**
 * Interface DataStoreInterface
 */
interface DataStoreInterface
{
    /**
     * @param string $collectionName
     */
    public function emptyDataStore($collectionName);

    /**
     * @param string $collectionName
     * @param array  $fixtures
     *
     * @return
     */
    public function persist($collectionName, $fixtures);


    /**
     * @param string $collectionName
     *
     * @return array
     */
    public function fetchCollection($collectionName);
}
