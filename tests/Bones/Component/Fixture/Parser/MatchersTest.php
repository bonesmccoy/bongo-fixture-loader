<?php

namespace tests\Bones\Component\Fixture\Parser;

use Bones\Component\Fixture\Parser\IdentityMatcher;
use Bones\Component\Fixture\Parser\SelfReferenceMatcher;

class MatchersTest extends \PHPUnit_Framework_TestCase
{

    public function testIdentityMatcher()
    {
        $identityMatcher = new IdentityMatcher(strtotime('now'));
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
        $identityMatcher = new IdentityMatcher($timestamp);

        $objectId =  $identityMatcher->convert("_id", 1);

        $selfReferenceMatcher = new SelfReferenceMatcher($timestamp);

        $this->assertTrue(
            $selfReferenceMatcher->match("wathever", "_self[1]")
        );

        $this->assertEquals(
            (string)$objectId,
            $selfReferenceMatcher->convert("wathever", "_self[1]")
        );

    }
}