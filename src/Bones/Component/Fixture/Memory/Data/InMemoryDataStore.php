<?php

namespace Bones\Component\Fixture\Memory\Data;

use Bones\Component\Fixture\DataStoreInterface;

/**
 * Class InMemoryDataStore
 */
class InMemoryDataStore implements DataStoreInterface
{
    protected $data = array();

    /**
     * {@inheritdoc}
     */
    public function emptyDataStore($collectionName)
    {
        $this->data[$collectionName] = array();
    }

    /**
     * {@inheritdoc}
     */
    public function persist($collectionName, $fixtures)
    {
        $this->data[$collectionName] = $fixtures;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchCollection($collectionName)
    {
        return  (isset($this->data[$collectionName])) ? $this->data[$collectionName] : array();
    }
}
