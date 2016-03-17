<?php


namespace Bones\Component\Fixture\Memory\Matcher;


use Bones\Component\Fixture\Parser\TransformerInterface;

class ReferenceTransformer implements TransformerInterface
{

    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public function match($key, $value)
    {
        $matches = array();

        preg_match(self::REFERENCE_PATTERN, $value, $matches);

        return count($matches) > 1;
    }

    public function convert($key, $value)
    {
        $matches = array();

        preg_match(self::REFERENCE_PATTERN, $value, $matches);

        return $matches[1];
    }
}