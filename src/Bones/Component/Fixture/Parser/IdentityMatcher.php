<?php
/**
 * Created by PhpStorm.
 * User: bonesmccoy
 * Date: 17/03/2016
 * Time: 11:05
 */

namespace Bones\Component\Fixture\Parser;


use Bones\Component\Mongo\Utilities;

class IdentityMatcher implements MatcherInterface
{

    const KEY_ID = "_id";

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
        return ($key === self::KEY_ID);
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