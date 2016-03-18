<?php


namespace Bones\Component\Fixture\Parser\Translator;


class DateTimeTranslator extends AbstractTranslator
{

    const PATTERN = '/<(\d{4}\-\d{2}\-\d{2}( \d{2}:\d{2}:\d{2})?)>$/';


    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function convert($key, $value)
    {
        if (!$this->isValid($value)) {
            return $value;
        }

        $matches = array();
        preg_match(static::PATTERN, $value, $matches);

        if ($matches > 1) {
            return new \DateTime($matches[1]);
        }

        return $value;
    }
}