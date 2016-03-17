<?php

namespace Bones\Component\Fixture\Memory;

use Bones\Component\Fixture\Memory\Transformer\ReferenceTransformer;

class MatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testSelfReferenceMatcher()
    {
        $selfReferenceMatcher = new ReferenceTransformer();

        $this->assertTrue(
            $selfReferenceMatcher->match('wathever', 'ref:1')
        );

        $this->assertEquals(
            1,
            $selfReferenceMatcher->convert('wathever', 'ref:1')
        );
    }
}
