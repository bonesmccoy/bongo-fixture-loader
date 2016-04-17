<?php


namespace Bones\Component\Fixture\Parser\Transformer;

/**
 * Class DateTimeTransformer
 */
class DateTimeTransformer extends AbstractTransformer
{

    const PATTERN = '/<(\d{4}\-\d{2}\-\d{2}( \d{2}:\d{2}:\d{2})?)>$/';


    /**
     * @param string $key
     * @param string $value
     *
     * @return \MongoDate
     */
    public function convert($key, $value)
    {
        if (!$this->isValid($value)) {
            return $value;
        }

        $matches = array();
        preg_match(static::PATTERN, $value, $matches);

        if (count($matches) > 1) {
            return new \MongoDate(strtotime($matches[1]));
        }

        return $value;
    }
}
