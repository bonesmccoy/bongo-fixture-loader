<?php

namespace Bones\Component\Fixture\Mongo\Transformer;


use Bones\Component\Fixture\Parser\TransformerInterface;
use Bones\Component\Mongo\Utilities;

class ReferenceTransformer implements TransformerInterface
{



    private $timestamp;

    public function __construct($timestamp)
    {

        $this->timestamp = $timestamp;
    }

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
        if (is_array($value)) return $value;

        $matches = array();

        preg_match(self::REFERENCE_PATTERN, $value, $matches);

        $plainId = $matches[1];

        $mongoID = Utilities::generateMongoId($this->timestamp, $plainId);

        return $mongoID;
    }
}