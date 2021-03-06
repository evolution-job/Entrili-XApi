<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Tests\Fixtures\Data;

use Entrili\XApiBundle\Model\IRI;
use Entrili\XApiBundle\Model\LanguageMap;
use Entrili\XApiBundle\Model\Verb;

/**
 * xAPI verb fixtures.
 *
 * These fixtures are borrowed from the
 * {@link https://github.com/adlnet/xapi_Test Experience API Learning Record Store Conformance Test} package.
 */
class VerbFixtures
{
    public static function getTypicalVerb()
    {
        return new Verb(IRI::fromString('http://tincanapi.com/conformancetest/verbid'), LanguageMap::create(array('en-US' => 'test')));
    }

    public static function getVoidingVerb()
    {
        return new Verb(IRI::fromString('http://adlnet.gov/expapi/verbs/voided'), LanguageMap::create(array('en-US' => 'voided')));
    }

    public static function getIdVerb()
    {
        return new Verb(IRI::fromString('http://tincanapi.com/conformancetest/verbid'));
    }

    public static function getIdAndDisplayVerb()
    {
        return new Verb(IRI::fromString('http://tincanapi.com/conformancetest/verbid'), LanguageMap::create(array('en-US' => 'test')));
    }

    public static function getForQueryVerb()
    {
        return new Verb(IRI::fromString('http://tincanapi.com/conformancetest/verbid/forQuery'), LanguageMap::create(array('en-US' => 'for query')));
    }
}
