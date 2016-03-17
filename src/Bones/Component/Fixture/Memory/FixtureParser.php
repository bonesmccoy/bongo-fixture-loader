<?php

namespace Bones\Component\Fixture\Memory;

use Bones\Component\Fixture\Memory\Transformer\ReferenceTransformer;
use Bones\Component\Fixture\Parser\AbstractFixtureParser;

class FixtureParser extends AbstractFixtureParser
{
    public function __construct()
    {
        $this->addTransformer(new ReferenceTransformer());
    }
}
