<?php

namespace tests\Bones\Component\Fixture\Mongo;

use Bones\Component\Fixture\Parser\Translator\MongoIdTranslator;

class TranslatorTest extends \PHPUnit_Framework_TestCase
{
    public function testIdTranslator()
    {
        $identityMatcher = new MongoIdTranslator();
        $this->assertTrue(
            $identityMatcher->match('_id', '<id@56eb45003639330941000013>')
        );

        $this->assertInstanceOf(
            '\MongoId',
            $identityMatcher->convert('_id', '<id@56eb45003639330941000013>')
        );
    }

    public function testIdentityUnexpectedData()
    {
        $identityMatcher = new MongoIdTranslator();
        $this->assertFalse(
            $identityMatcher->match('_id', array('1'))
        );

        $this->assertEquals(
            array('1'),
            $identityMatcher->convert('_id', array('1'))
        );
    }
}
