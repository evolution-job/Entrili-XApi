<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Tests;

use Entrili\XApiBundle\Model\Actor;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class ActorSerializerTest extends SerializerTest
{
    private $actorSerializer;

    protected function setUp()
    {
        $this->actorSerializer = $this->createActorSerializer();
    }

    /**
     * @dataProvider serializeData
     * @param Actor $actor
     * @param $expectedJson
     */
    public function testSerializeActor(Actor $actor, $expectedJson)
    {
        $this->assertJsonStringEqualsJsonString($expectedJson, $this->actorSerializer->serializeActor($actor));
    }

    public function serializeData()
    {
        return $this->buildSerializeTestCases('Actor');
    }

    /**
     * @dataProvider deserializeData
     * @param $json
     * @param Actor $expectedActor
     */
    public function testDeserializeActor($json, Actor $expectedActor)
    {
        $actor = $this->actorSerializer->deserializeActor($json);

        $this->assertInstanceOf('Entrili\XApiBundle\Model\Actor', $actor);
        $this->assertTrue($expectedActor->equals($actor), 'Deserialized actor has the expected properties');
    }

    public function deserializeData()
    {
        return $this->buildDeserializeTestCases('Actor');
    }

    abstract protected function createActorSerializer();
}
