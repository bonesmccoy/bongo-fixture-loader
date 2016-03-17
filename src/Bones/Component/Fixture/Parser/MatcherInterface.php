<?php

namespace Bones\Component\Fixture\Parser;

interface MatcherInterface
{
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