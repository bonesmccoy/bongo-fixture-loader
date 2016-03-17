<?php

namespace tests\bin;

use Bones\Component\Fixture\FixtureLoader;

class TestBinaryFile extends \PHPUnit_Framework_TestCase
{
    public function testLoaderFromConfiguration()
    {
        $loader = FixtureLoader::factoryMongoFixtureLoader(
            __DIR__.'config.yml'
        );
    }

    public function testLoaderFromConfigurationAndAddFixture()
    {
        $loader = FixtureLoader::factoryMongoFixtureLoader(
            __DIR__.'config.yml'
        );

        $loader->addSingleFixture(
            array('collection' => array('_id' => 1, 'name' => 'brian'),
            )
        );
    }
}
