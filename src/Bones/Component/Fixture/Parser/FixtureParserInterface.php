<?php

namespace Bones\Component\Fixture\Parser;

interface FixtureParserInterface
{
    /**
     * @param $fixture
     *
     * @return array
     */
    public function parse($fixture);

    /**
     * @param TransformerInterface $transformer
     */
    public function addTransformer(TransformerInterface $transformer);
}
