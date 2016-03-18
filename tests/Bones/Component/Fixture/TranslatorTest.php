<?php

namespace tests\Bones\Component\Fixture\Mongo;

use Bones\Component\Fixture\Parser\Translator\DateTimeTranslator;
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

    public function testDateTimeTranslator()
    {

        $dateTimeTranslator = new DateTimeTranslator();
        $this->assertFalse(
            $dateTimeTranslator->match("someField", "1")
        );

        $this->assertTrue(
            $dateTimeTranslator->match("someField", '<2015-01-01 00:00:00>')
        );

        $this->assertTrue(
            $dateTimeTranslator->match("someField", '<2015-01-01>')
        );

        $this->assertInstanceOf(
            '\DateTime',
            $dateTimeTranslator->convert("someField", '<2015-01-01 00:00:00>')
        );

        $this->assertInstanceOf(
            '\DateTime',
            $dateTimeTranslator->convert("someField", '<2015-01-01>')
        );
    }

    public function testDateTimeWrong()
    {
        $dateTimeTranslator = new DateTimeTranslator();
        $this->assertFalse(
            $dateTimeTranslator->match("someField", "2015-1-2 0:00:00")
        );
    }
}
