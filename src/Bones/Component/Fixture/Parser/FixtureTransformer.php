<?php

namespace Bones\Component\Fixture\Parser;

use Bones\Component\Fixture\Parser\Transformer\DateTimeTransformer;
use Bones\Component\Fixture\Parser\Transformer\MongoIdTransformer;

class FixtureTransformer extends AbstractFixtureTransformer
{

    /**
     * FixtureParser constructor.
     */
    public function __construct()
    {
        $this->addTranslator(new MongoIdTransformer());
        $this->addTranslator(new DateTimeTransformer());
    }
}
