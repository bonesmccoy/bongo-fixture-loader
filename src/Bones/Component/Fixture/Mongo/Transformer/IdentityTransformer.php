<?php

namespace Bones\Component\Fixture\Mongo\Transformer;

use Bones\Component\Fixture\Parser\TransformerInterface;
use Bones\Component\Mongo\Utilities;

class IdentityTransformer implements TransformerInterface
{
    private $timestamp;

    public function __construct($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return bool
     */
    public function match($key, $value)
    {
        if (is_array($value)) {
            return false;
        }

        return $key === self::IDENTITY_PATTERN;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return \MongoId
     */
    public function convert($key, $value)
    {
        if (is_array($value)) {
            return $value;
        }

        try {
            $id = new \MongoId($value);
        } Catch(\MongoException $e) {
            $id = Utilities::generateMongoId($this->timestamp, $value);
        }

        return $id;
    }
}
