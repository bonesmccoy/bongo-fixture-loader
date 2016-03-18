<?php

namespace Bones\Component\Fixture\Parser;

abstract class AbstractFixtureParser implements FixtureParserInterface
{
    /**
     * @var TranslatorInterface[]
     */
    protected $transformers;

    public function parse($fixture)
    {
        foreach ($fixture as $key => $value) {
            $fixture[$key] = $this->strategy($key, $value);
        }

        return $fixture;
    }

    private function strategy($key, $value)
    {
        /** @var TranslatorInterface $matcher */
        foreach ($this->transformers as $matcher) {
            if ($matcher->match($key, $value)) {
                return $matcher->convert($key, $value);
            }
        }

        return $value;
    }

    /**
     * @param TranslatorInterface $transformer
     */
    public function addTransformer(TranslatorInterface $transformer)
    {
        $this->transformers[] = $transformer;
    }
}
