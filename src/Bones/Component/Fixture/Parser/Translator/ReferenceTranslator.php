<?php

namespace Bones\Component\Fixture\Mongo\Transformer;

use Bones\Component\Fixture\Parser\TranslatorInterface;
use Bones\Component\Mongo\Utilities;

class ReferenceTranslator implements TranslatorInterface
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

        $matches = array();
        preg_match(self::REFERENCE_PATTERN, $value, $matches);

        return count($matches) > 1;
    }

    public function convert($key, $value)
    {
        if (is_array($value)) {
            return $value;
        }

        $matches = array();
        preg_match(self::REFERENCE_PATTERN, $value, $matches);

        $plainId = $matches[1];

        try {
            $mongoId = new \MongoId($plainId);
        } Catch(\MongoException $e) {
            $mongoId = Utilities::generateMongoId($this->timestamp, $plainId);
        }

        return $mongoId;
    }
}
