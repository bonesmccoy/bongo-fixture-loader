<?php


namespace Bones\Component\Fixture\Mongo\Matcher;


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
     * @return bool
     */
    public function match($key, $value)
    {
        return ($key === self::IDENTITY_PATTERN);
    }

    /**
     * @param $key
     * @param $value
     * @return \MongoId
     */
    public function convert($key, $value)
    {
        return Utilities::generateMongoId($this->timestamp, $value);
    }
}