<?php
/**
 * Created by PhpStorm.
 * User: bonesmccoy
 * Date: 17/03/2016
 * Time: 10:57
 */

namespace Bones\Component\Fixture\Mongo;


use Bones\Component\Fixture\Mongo\Matcher\IdentityTransformer;
use Bones\Component\Fixture\Mongo\Matcher\ReferenceTransformer;
use Bones\Component\Fixture\Parser\FixtureParserInterface;
use Bones\Component\Fixture\Parser\TransformerInterface;


class FixtureParser implements FixtureParserInterface
{

    /**
     * @var TransformerInterface[]
     */
    private $transformers = array();

    private $timestamp;

    /**
     * FixtureParser constructor.
     */
    public function __construct()
    {
        $this->timestamp = strtotime('now');
        $this->addTransformer(new IdentityTransformer($this->timestamp));
        $this->addTransformer(new ReferenceTransformer($this->timestamp));
    }

    public function parse($fixture) {

        foreach($fixture as $key => $value) {
            $fixture[$key] = $this->strategy($key, $value);
        }

        return $fixture;
    }


    private function strategy($key, $value)
    {
        /** @var TransformerInterface $matcher */
        foreach ($this->transformers as $matcher) {
            if ($matcher->match($key, $value)) {
                return $matcher->convert($key, $value);
            }
        }

        return $value;
    }

    /**
     * @param TransformerInterface $transformer
     */
    public function addTransformer(TransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;
    }
}