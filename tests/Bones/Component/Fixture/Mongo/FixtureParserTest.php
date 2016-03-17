<?php

namespace tests\Bones\Component\Fixture\Mongo;

use Bones\Component\Fixture\Mongo\FixtureParser;

class FixtureParserTest extends \PHPUnit_Framework_TestCase
{
    public function testFixtureWithId()
    {
        $fixture = array(
            '_id' => 1,
            'name' => 'John',
        );

        $parser = new FixtureParser();

        $parsedFixture = $parser->parse($fixture);

        $this->assertArrayHasKey('_id', $parsedFixture);
        $this->assertInstanceOf(
            '\MongoId',
            $parsedFixture['_id']
        );
    }

    public function testFixtureWithIdAndReference()
    {
        $fixture = array(
            '_id' => 1,
            'name' => 'John',
            'parentId' => 'ref:1',
        );

        $parser = new FixtureParser();

        $parsedFixture = $parser->parse($fixture);

        $this->assertArrayHasKey('_id', $parsedFixture);
        $this->assertArrayHasKey('parentId', $parsedFixture);
        $this->assertInstanceOf(
            '\MongoId',
            $parsedFixture['_id']
        );

        $this->assertInstanceOf(
            '\MongoId',
            $parsedFixture['parentId']
        );
    }
}
