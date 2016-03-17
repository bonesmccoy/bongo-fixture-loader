<?php
/**
 * Created by PhpStorm.
 * User: bonesmccoy
 * Date: 17/03/2016
 * Time: 10:57
 */

namespace Bones\Component\Fixture\Parser;


use Bones\Component\Mongo\Utilities;

class Parser
{

    /**
     * @var MatcherInterface[]
     */
    private $matchers = array();

    private $timestamp;

    public function __construct()
    {
        $this->timestamp = strtotime('now');

        $this->matchers[] = new IdentityMatcher($this->timestamp);

    }

    public function parse($fixture) {

        foreach($fixture as $key => $value) {
            $fixture[$key] = $this->strategy($key, $value);
        }

        return $fixture;
    }


    private function strategy($key, $value)
    {
        /** @var MatcherInterface $matcher */
        foreach ($this->matchers as $matcher) {
            if ($matcher->match($key, $value)) {
                return $matcher->convert($key, $value);
            }
        }
    }
}