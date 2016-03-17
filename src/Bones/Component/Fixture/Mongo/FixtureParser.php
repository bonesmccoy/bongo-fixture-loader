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
use Bones\Component\Fixture\Parser\AbstractFixtureParser;
use Bones\Component\Fixture\Parser\FixtureParserInterface;
use Bones\Component\Fixture\Parser\TransformerInterface;


class FixtureParser extends AbstractFixtureParser
{


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


}