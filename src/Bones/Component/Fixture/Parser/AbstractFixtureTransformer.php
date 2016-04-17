<?php

namespace Bones\Component\Fixture\Parser;

use Bones\Component\Fixture\Parser\Transformer\TransformerInterface;

/**
 * Class AbstractFixtureTransformer
 */
abstract class AbstractFixtureTransformer implements FixtureTransformerInterface
{
    /**
     * @var TransformerInterface[]
     */
    protected $translators;

    /**
     * {@inheritdoc}
     */
    public function parse(array $fixture)
    {
        foreach ($fixture as $key => $value) {
            $fixture[$key] = $this->strategy($key, $value);
        }

        return $fixture;
    }

    /**
     * {@inheritdoc}
     */
    public function addTransformer(TransformerInterface $transformer)
    {
        $this->translators[] = $transformer;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return mixed
     */
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
}
