<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Serializer;

use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Entrili\XApiBundle\Model\Actor;
use Entrili\XApiBundle\Serializer\ActorSerializerInterface;
use Entrili\XApiBundle\Serializer\Exception\ActorDeserializationException;
use Entrili\XApiBundle\Serializer\Exception\ActorSerializationException;

/**
 * Serializes and deserializes {@link Actor actors} using the Symfony Serializer component.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class ActorSerializer implements ActorSerializerInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function serializeActor(Actor $actor)
    {
        try {
            return $this->serializer->serialize($actor, 'json');
        } catch (ExceptionInterface $e) {
            throw new ActorSerializationException($e->getMessage(), 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deserializeActor($data)
    {
        try {
            return $this->serializer->deserialize($data, 'Entrili\XApiBundle\Model\Actor', 'json');
        } catch (ExceptionInterface $e) {
            throw new ActorDeserializationException($e->getMessage(), 0, $e);
        }
    }
}
