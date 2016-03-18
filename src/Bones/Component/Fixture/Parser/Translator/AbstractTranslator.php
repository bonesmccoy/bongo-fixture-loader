<?php

namespace Bones\Component\Fixture\Parser\Translator;


abstract class AbstractTranslator implements TranslatorInterface
{

    const PATTERN = '';

    /**
     * @param $key
     * @param $value
     *
     * @return bool
     */
    public function match($key, $value)
    {
        if (!$this->isValid($value)) {
            return false;
        }

        $matches = array();
        preg_match(self::PATTERN, $value, $matches);

        return count($matches) > 1;
    }

    /**
     * @param $value
     * @return bool
     */
    protected function isValid($value)
    {
        if (is_array($value)) { return false; }
    }
}