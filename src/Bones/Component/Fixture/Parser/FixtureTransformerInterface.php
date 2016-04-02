<?php

namespace Bones\Component\Fixture\Parser;

use Bones\Component\Fixture\Parser\Transformer\TransformerInterface;

interface FixtureTransformerInterface
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
    public function addTranslator(TransformerInterface $transformer);
}
