<?php

namespace Bones\Component\Fixture\Parser;

use Bones\Component\Fixture\Parser\Translator\TranslatorInterface;

abstract class AbstractFixtureParser implements FixtureParserInterface
{
    /**
     * @var TranslatorInterface[]
     */
    protected $translators;

    public function parse($fixture)
    {
        foreach ($fixture as $key => $value) {
            $fixture[$key] = $this->strategy($key, $value);
        }

        return $fixture;
    }

    private function strategy($key, $value)
    {
        /** @var TranslatorInterface $translator */
        foreach ($this->translators as $translator) {
            if ($translator->match($key, $value)) {
                return $translator->convert($key, $value);
            }
        }

        return $value;
    }

    /**
     * @param TranslatorInterface $transformer
     */
    public function addTranslator(TranslatorInterface $transformer)
    {
        $this->translators[] = $transformer;
    }
}
