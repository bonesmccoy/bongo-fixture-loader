<?php

namespace tests\Bones\Component\Fixture\Mongo;

use Bones\Component\Fixture\Mongo\Transformer\IdentityTransformer;
use Bones\Component\Fixture\Mongo\Transformer\ReferenceTransformer;

class TransformersTest extends \PHPUnit_Framework_TestCase
{
    public function testIdentityMatcher()
    {
        $identityMatcher = new IdentityTransformer(strtotime('today'));
        $this->assertTrue(
            $identityMatcher->match('_id', 1)
        );

        $this->assertInstanceOf(
            '\MongoId',
            $identityMatcher->convert('_id', 1)
        );
    }

    public function testIdentityUnexpectedData()
    {
        $identityMatcher = new IdentityTransformer(strtotime('today'));
        $this->assertFalse(
            $identityMatcher->match('_id', array('1'))
        );

        $this->assertEquals(
            array('1'),
            $identityMatcher->convert('_id', array('1'))
        );
    }

    public function testSelfReferenceMatcher()
    {
        $timestamp = strtotime('now');
        $identityMatcher = new IdentityTransformer($timestamp);

        $objectId = $identityMatcher->convert('_id', 1);

        $selfReferenceMatcher = new ReferenceTransformer($timestamp);

        $this->assertTrue(
            $selfReferenceMatcher->match('wathever', 'ref:1')
        );

        $this->assertEquals(
            $objectId,
            $selfReferenceMatcher->convert('wathever', 'ref:1')
        );
    }

    public function testReferenceBadData()
    {
        $timestamp = strtotime('today');
        $selfReferenceMatcher = new ReferenceTransformer($timestamp);

        $this->assertFalse(
            $selfReferenceMatcher->match('wathever', array('2'))
        );

        $this->assertEquals(
            array('2'),
            $selfReferenceMatcher->convert('wathever', array('2'))
        );
    }

    public function testReferenceWithMongoId()
    {
        $timestamp = strtotime('now');
        $identityMatcher = new IdentityTransformer($timestamp);

        $objectId = $identityMatcher->convert('_id', '56eb45003639330941000001');

        $selfReferenceMatcher = new ReferenceTransformer($timestamp);

        $this->assertTrue(
            $selfReferenceMatcher->match('wathever', 'ref:56eb45003639330941000001')
        );

        $this->assertEquals(
            $objectId,
            $selfReferenceMatcher->convert('wathever', 'ref:56eb45003639330941000001')
        );
    }
}
