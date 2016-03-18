<?php

namespace Bones\Component\Fixture\Mongo;

use Bones\Component\Fixture\Mongo\Transformer\IdentityTranslator;
use Bones\Component\Fixture\Mongo\Transformer\ReferenceTranslator;
use Bones\Component\Fixture\Parser\AbstractFixtureParser;
use Bones\Component\Fixture\Parser\Translator\MongoIdTranslator;

class FixtureParser extends AbstractFixtureParser
{

    /**
     * FixtureParser constructor.
     */
    public function __construct()
    {
        $this->addTransformer(new MongoIdTranslator());
    }
}
