<?php

namespace Bones\Component\Fixture\Parser;

use Bones\Component\Fixture\Parser\Transformer\TransformerInterface;

abstract class AbstractFixtureTransformer implements FixtureTransformerInterface
{
    /**
     * @var TransformerInterface[]
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
        /** @var TransformerInterface $translator */
        foreach ($this->translators as $translator) {
            if ($translator->match($key, $value)) {
                return $translator->convert($key, $value);
            }
        }

        return $value;
    }

    /**
     * @param TransformerInterface $transformer
     */
    public function addTranslator(TransformerInterface $transformer)
    {
        $this->translators[] = $transformer;
    }
}
