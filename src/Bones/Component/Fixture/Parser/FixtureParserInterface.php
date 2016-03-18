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
     * @param TranslatorInterface $transformer
     */
    public function addTransformer(TranslatorInterface $transformer);
}
