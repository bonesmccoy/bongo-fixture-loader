<?php

namespace Bones\Component\Fixture\Mongo;

use Bones\Component\Fixture\Mongo\Transformer\IdentityTransformer;
use Bones\Component\Fixture\Mongo\Transformer\ReferenceTransformer;
use Bones\Component\Fixture\Parser\AbstractFixtureParser;

class FixtureParser extends AbstractFixtureParser
{
    private $timestamp;

    /**
     * FixtureParser constructor.
     */
    public function __construct()
    {
        $this->timestamp = strtotime('today');
        $this->addTransformer(new IdentityTransformer($this->timestamp));
        $this->addTransformer(new ReferenceTransformer($this->timestamp));
    }
}
