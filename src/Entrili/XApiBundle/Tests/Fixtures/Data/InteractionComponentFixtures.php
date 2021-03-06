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

use Entrili\XApiBundle\Model\Interaction\InteractionComponent;
use Entrili\XApiBundle\Model\LanguageMap;

/**
 * xAPI statement activity interaction component fixtures.
 *
 * These fixtures are borrowed from the
 * {@link https://github.com/adlnet/xapi_Test Experience API Learning Record Store Conformance Test} package.
 */
class InteractionComponentFixtures
{
    public static function getTypicalInteractionComponent()
    {
        return new InteractionComponent('test');
    }

    public static function getIdOnlyInteractionComponent()
    {
        return new InteractionComponent('test');
    }

    public static function getAllPropertiesInteractionComponent()
    {
        return new InteractionComponent('test', LanguageMap::create(array('en-US' => 'test')));
    }
}
