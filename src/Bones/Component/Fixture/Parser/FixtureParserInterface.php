<?php

namespace Bones\Component\Fixture\Parser;

use Bones\Component\Fixture\Parser\Translator\TranslatorInterface;

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
    public function addTranslator(TranslatorInterface $transformer);
}
