<?php

namespace Bones\Component\Fixture\Parser\Translator;

use Bones\Component\Fixture\Parser\TranslatorInterface;
use Bones\Component\Mongo\Utilities;

/**
 * parse fixture value with structure <id@{hexMongoId}> and creates a MongoId
 *
 * Class IdTranslator
 * @package Bones\Component\Fixture\Parser\Translator
 */

class MongoIdTranslator extends AbstractTranslator
{
    const PATTERN = '/^<id@([]{,12})>$/';


    /**
     * @param $key
     * @param $value
     *
     * @return \MongoId
     */
    public function convert($key, $value)
    {
        $matches = array();
        preg_match(self::PATTERN, $value, $matches);

        $plainId = $matches[1];

        return new \MongoId($plainId);
    }
}
