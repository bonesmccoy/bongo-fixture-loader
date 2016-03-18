<?php

namespace Bones\Component\Fixture\Memory;

use Bones\Component\Fixture\Parser\AbstractFixtureParser;
use Bones\Component\Fixture\Parser\Translator\MongoIdTranslator;

class FixtureParser extends AbstractFixtureParser
{
    public function __construct()
    {
        $this->addTransformer(new MongoIdTranslator());
    }
}
