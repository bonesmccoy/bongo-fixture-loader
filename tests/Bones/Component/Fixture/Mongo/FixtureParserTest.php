<?php

namespace tests\Bones\Component\Fixture\Mongo;



use Bones\Component\Fixture\Parser\FixtureParser;

class FixtureParserTest extends \PHPUnit_Framework_TestCase
{
    public function testFixtureWithId()
    {
        $fixture = array(
            '_id' => '<id@56eb45003639330941000013>',
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
            '_id' => '<id@56eb45003639330941000013>',
            'name' => 'John',
            'parentId' => '<id@56eb45003639330941000014>',
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
