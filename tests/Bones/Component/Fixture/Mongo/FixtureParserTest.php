<?php

namespace tests\Bones\Component\Fixture\Mongo;

use Bones\Component\Fixture\Parser\FixtureTransformer;

/**
 * Class FixtureParserTest
 */
class FixtureParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test \Bones\Component\Fixture\Parser\Transformer\MongoIdTransformer::convert()
     */
    public function testFixtureWithId()
    {
        $fixture = array(
            '_id' => '<id@56eb45003639330941000013>',
            'name' => 'John',
        );

        $parser = new FixtureTransformer();

        $parsedFixture = $parser->parse($fixture);

        $this->assertArrayHasKey('_id', $parsedFixture);
        $this->assertInstanceOf(
            '\MongoId',
            $parsedFixture['_id']
        );
    }

    /**
     * @test \Bones\Component\Fixture\Parser\Transformer\MongoIdTransformer::convert()
     */
    public function testFixtureWithIdAndReference()
    {
        $fixture = array(
            '_id' => '<id@56eb45003639330941000013>',
            'name' => 'John',
            'parentId' => '<id@56eb45003639330941000014>',
        );

        $parser = new FixtureTransformer();

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
