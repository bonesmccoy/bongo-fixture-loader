<?php

namespace Bones\Component\Fixture\Parser;

use Bones\Component\Fixture\Parser\Transformer\DateTimeTransformer;
use Bones\Component\Fixture\Parser\Transformer\MongoIdTransformer;

/**
 * Class FixtureTransformer
 */
class FixtureTransformer extends AbstractFixtureTransformer
{

    /**
     * FixtureParser constructor.
     */
    public function __construct()
    {
        $this->addTransformer(new MongoIdTransformer());
        $this->addTransformer(new DateTimeTransformer());
    }
}
