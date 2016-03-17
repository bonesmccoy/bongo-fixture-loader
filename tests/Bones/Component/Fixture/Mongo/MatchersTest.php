<?php

namespace tests\Bones\Component\Fixture\Mongo;


use Bones\Component\Fixture\Mongo\Matcher\IdentityTransformer;
use Bones\Component\Fixture\Mongo\Matcher\ReferenceTransformer;

class MatchersTest extends \PHPUnit_Framework_TestCase
{

    public function testIdentityMatcher()
    {
        $identityMatcher = new IdentityTransformer(strtotime('now'));
        $this->assertTrue(
            $identityMatcher->match("_id", 1)
        );

        $this->assertInstanceOf(
            '\MongoId',
            $identityMatcher->convert("_id", 1)
        );
    }

    public function testSelfReferenceMatcher()
    {
        $timestamp = strtotime('now');
        $identityMatcher = new IdentityTransformer($timestamp);

        $objectId =  $identityMatcher->convert("_id", 1);

        $selfReferenceMatcher = new ReferenceTransformer($timestamp);

        $this->assertTrue(
            $selfReferenceMatcher->match("wathever", "ref:1")
        );

        $this->assertEquals(
            $objectId,
            $selfReferenceMatcher->convert("wathever", "ref:1")
        );

    }
}