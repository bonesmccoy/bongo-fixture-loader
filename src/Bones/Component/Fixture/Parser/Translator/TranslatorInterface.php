<?php

namespace Bones\Component\Fixture\Parser\Translator;

interface TranslatorInterface
{
    const REFERENCE_PATTERN = '/^ref:([0-9A-fa-f]+)$/';
    const IDENTITY_PATTERN = '_id';

    /**
     * @param $key
     * @param $value
     *
     * @return bool
     */
    public function match($key, $value);

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function convert($key, $value);
}
