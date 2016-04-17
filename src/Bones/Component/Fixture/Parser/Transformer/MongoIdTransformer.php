<?php

namespace Bones\Component\Fixture\Parser\Transformer;

/**
 * parse fixture value with structure <id@{hexMongoId}> and creates a MongoId
 *
 * Class MongoIdTransformer
 * @package Bones\Component\Fixture\Parser\Transformer
 */

class MongoIdTransformer extends AbstractTransformer
{
    const PATTERN = '/^<id@([0-9A-fa-f]{24})>$/i';


    /**
     * @param string $key
     * @param string $value
     *
     * @return \MongoId
     */
    public function convert($key, $value)
    {
        if (!$this->isValid($value)) {
            return $value;
        }

        $matches = array();
        preg_match(self::PATTERN, $value, $matches);

        $plainId = $matches[1];

        return new \MongoId($plainId);
    }
}
