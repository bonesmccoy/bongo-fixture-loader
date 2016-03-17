<?php


namespace Bones\Component\Fixture\Mongo;


use Bones\Component\Fixture\FixtureParser;
use Bones\Component\Fixture\Mongo\Matcher\IdentityTransformer;
use Bones\Component\Fixture\Mongo\Matcher\ReferenceTransformer;

class FixtureParserTest extends \PHPUnit_Framework_TestCase
{

    public function testFixtureWithId()
    {
        $fixture = array(
            '_id' => 1,
            'name' => 'John'
        );

        $parser = new FixtureParser();

        $timestamp = strtotime('now');
        $parser->addTransformer(new IdentityTransformer($timestamp));
        $parser->addTransformer(new ReferenceTransformer($timestamp));


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
            'parentId' => 'ref:1'
        );

        $parser = new FixtureParser();

        $timestamp = strtotime('now');
        $parser->addTransformer(new IdentityTransformer($timestamp));
        $parser->addTransformer(new ReferenceTransformer($timestamp));


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