<?php


namespace test\Bones\Component\Fixture\Memory\Data;


use Bones\Component\Fixture\Memory\Data\InMemoryDataStore;

class InMemoryDataStoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InMemoryDataStore
     */
    protected $dataStore;

    public function setUp()
    {
        $this->dataStore = new InMemoryDataStore();

        $this->dataStore->persist('collection', array(
            array("id" => 1)
        ));
    }

    public function testPersist()
    {
        $storedData = $this->dataStore->fetchCollection('collection');

        $this->assertCount(
            1,
            $storedData
        );

        $this->assertArrayHasKey(
            'id',
            $storedData[0]
        );

    }

    public function testEmptyDataStore()
    {
        $this->assertCount(
            1,
            $this->dataStore->fetchCollection('collection')
        );
        $this->dataStore->emptyDataStore('collection');
        $this->assertCount(
            0,
            $this->dataStore->fetchCollection('collection')
        );

    }
}