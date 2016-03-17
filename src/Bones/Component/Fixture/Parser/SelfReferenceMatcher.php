<?php
/**
 * Created by PhpStorm.
 * User: bonesmccoy
 * Date: 17/03/2016
 * Time: 11:05
 */

namespace Bones\Component\Fixture\Parser;


use Bones\Component\Mongo\Utilities;

class SelfReferenceMatcher implements MatcherInterface
{

    const PATTERN = '/^\_self\[([0-9])+\]$/';

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

        preg_match(self::PATTERN, $value, $matches);

        return count($matches) > 1;
    }

    public function convert($key, $value)
    {
        $matches = array();

        preg_match(self::PATTERN, $value, $matches);

        $plainId = $matches[1];

        $mongoID = Utilities::generateMongoId($this->timestamp, $plainId);

        return (string) $mongoID;
    }
}