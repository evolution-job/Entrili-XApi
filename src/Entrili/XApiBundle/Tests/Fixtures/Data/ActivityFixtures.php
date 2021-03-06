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

use Entrili\XApiBundle\Model\Activity;
use Entrili\XApiBundle\Model\IRI;

/**
 * xAPI statement activity fixtures.
 *
 * These fixtures are borrowed from the
 * {@link https://github.com/adlnet/xapi_Test Experience API Learning Record Store Conformance Test} package.
 */
class ActivityFixtures
{
    public static function getTypicalActivity()
    {
        return new Activity(IRI::fromString('http://tincanapi.com/conformancetest/activityid'));
    }

    public static function getIdActivity()
    {
        return new Activity(IRI::fromString('http://tincanapi.com/conformancetest/activityid'));
    }

    public static function getIdAndDefinitionActivity()
    {
        return new Activity(IRI::fromString('http://tincanapi.com/conformancetest/activityid'), DefinitionFixtures::getTypicalDefinition());
    }

    public static function getAllPropertiesActivity()
    {
        return new Activity(IRI::fromString('http://tincanapi.com/conformancetest/activityid'), DefinitionFixtures::getTypicalDefinition());
    }

    public static function getForQueryActivity()
    {
        return new Activity(IRI::fromString('http://tincanapi.com/conformancetest/activityid/forQuery'), DefinitionFixtures::getForQueryDefinition());
    }
}
