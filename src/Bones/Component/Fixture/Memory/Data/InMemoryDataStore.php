<?php

namespace Bones\Component\Fixture\Memory\Data;

use Bones\Component\Fixture\DataStoreInterface;
use Bones\Component\Fixture\Parser\FixtureParserInterface;

class InMemoryDataStore implements DataStoreInterface
{
    protected $data = array();
    /**
     * @var FixtureParserInterface
     */
    private $fixtureParser;

    public function __construct(FixtureParserInterface $fixtureParser)
    {

        $this->fixtureParser = $fixtureParser;
    }

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
        $fixtures = $this->applyParsing($fixtures);
        $this->data[$collection] = $fixtures;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $fixtures
     * @return mixed
     */
    private function applyParsing($fixtures)
    {
        foreach ($fixtures as $id => $fixture) {
            $fixtures[$id] = $this->fixtureParser->parse($fixture);
        }
        return $fixtures;
    }
}
