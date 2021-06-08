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

use Ramsey\Uuid\Uuid as RamseyUuid;

/**
 * xAPI UUID fixtures.
 *
 * These fixtures are borrowed from the
 * {@link https://github.com/adlnet/xapi_Test Experience API Learning Record Store Conformance Test} package.
 */
class UuidFixtures
{
    public static function getGoodUuid()
    {
        return '39e24cc4-69af-4b01-a824-1fdc6ea8a3af';
    }

    public static function getBadUuid()
    {
        return 'bad-uuid';
    }

    public static function getUniqueUuid()
    {
        return (string)RamseyUuid::uuid4();
    }
}
