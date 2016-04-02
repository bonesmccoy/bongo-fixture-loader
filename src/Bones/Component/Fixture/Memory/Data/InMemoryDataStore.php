<?php

namespace Bones\Component\Fixture\Memory\Data;

use Bones\Component\Fixture\DataStoreInterface;

class InMemoryDataStore implements DataStoreInterface
{
    protected $data = array();

    /**
     * @param $collection
     */
    public function emptyDataStore($collection)
    {
        $this->data[$collection] = array();
    }

    /**
     * @param $collection
     * @param array $fixtures
     */
    public function persist($collection, $fixtures)
    {
        $this->data[$collection] = $fixtures;
    }

    /**
     * @param $collection
     * @return array|mixed
     */
    public function fetchCollection($collection)
    {
        return  (isset($this->data[$collection])) ? $this->data[$collection] : array();
    }

}
