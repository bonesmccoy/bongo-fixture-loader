<?php

namespace Bones\Component\Fixture\Parser;

use Bones\Component\Fixture\Parser\Transformer\TransformerInterface;

/**
 * Interface FixtureTransformerInterface
 *
 */
interface FixtureTransformerInterface
{
    /**
     * @param array $fixture
     *
     * @return array
     */
    public function parse(array $fixture);

    /**
     * @param TransformerInterface $transformer
     */
    public function addTransformer(TransformerInterface $transformer);
}
