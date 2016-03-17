<?php

namespace Bones\Component\Fixture\Parser;

interface TransformerInterface
{
    const REFERENCE_PATTERN = '/^ref:([0-9])+$/';
    const IDENTITY_PATTERN = "_id";

    /**
     * @param $key
     * @param $value
     * @return boolean
     */
    public function match($key, $value);

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function convert($key, $value);
}