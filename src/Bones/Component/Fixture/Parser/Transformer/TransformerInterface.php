<?php

namespace Bones\Component\Fixture\Parser\Transformer;

/**
 * Interface TransformerInterface
 */
interface TransformerInterface
{
    const REFERENCE_PATTERN = '/^ref:([0-9A-fa-f]+)$/';
    const IDENTITY_PATTERN = '_id';

    /**
     * @param string $key
     * @param string $value
     *
     * @return bool
     */
    public function match($key, $value);

    /**
     * @param string $key
     * @param string $value
     *
     * @return mixed
     */
    public function convert($key, $value);
}
