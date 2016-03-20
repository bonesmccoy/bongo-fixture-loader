<?php

namespace Bones\Component\Fixture\Parser\Transformer;


abstract class AbstractTransformer implements TransformerInterface
{

    const PATTERN = '';

    /**
     * @param $key
     * @param $value
     *
     * @return bool
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
     * @return bool
     */
    protected function isValid($value)
    {
        if (is_array($value)) { return false; }

        return true;
    }
}