<?php

namespace tests\Bones\Component\Fixture\Mongo;

use Bones\Component\Fixture\Parser\Transformer\DateTimeTransformer;
use Bones\Component\Fixture\Parser\Transformer\MongoIdTransformer;

class TransformersTest extends \PHPUnit_Framework_TestCase
{
    public function testIdTranslator()
    {
        $identityMatcher = new MongoIdTransformer();
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
        $identityMatcher = new MongoIdTransformer();
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

        $dateTimeTranslator = new DateTimeTransformer();
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
            '\MongoDate',
            $dateTimeTranslator->convert("someField", '<2015-01-01 00:00:00>')
        );

        $this->assertInstanceOf(
            '\MongoDate',
            $dateTimeTranslator->convert("someField", '<2015-01-01>')
        );
    }

    public function testDateTimeWrong()
    {
        $dateTimeTranslator = new DateTimeTransformer();
        $this->assertFalse(
            $dateTimeTranslator->match("someField", "2015-1-2 0:00:00")
        );

        $dateTimeTranslator = new DateTimeTransformer();
        $this->assertEquals(
            '2015-1-2 0:00:00',
            $dateTimeTranslator->convert("someField", "2015-1-2 0:00:00")
        );
    }
}
