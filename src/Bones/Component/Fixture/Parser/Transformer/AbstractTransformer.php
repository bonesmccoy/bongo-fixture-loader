<?php

namespace Bones\Component\Fixture\Parser\Transformer;

/**
 * Class AbstractTransformer
 */
abstract class AbstractTransformer implements TransformerInterface
{

    const PATTERN = '';

    /**
     * {@inheritdoc}
     */
    public function match($key, $value)
    {
        if (!$this->isValid($value)) {
            return false;
        }

        $matches = array();
        preg_match(static::PATTERN, $value, $matches);

        return count($matches) > 1;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    protected function isValid($value)
    {
        return is_array($value);
    }
}
