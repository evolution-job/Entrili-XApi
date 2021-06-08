<?php

namespace Entrili\XApiBundle\Serializer;

use Entrili\XApiBundle\Model\State;
use Entrili\XApiBundle\Serializer\Exception\StateDeserializationException;
use Entrili\XApiBundle\Serializer\Exception\StateSerializationException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * Serializes and deserializes {@link State states} using the Symfony Serializer component.
 */
final class StateSerializer implements StateSerializerInterface
{
    /**
     * @var SerializerInterface The underlying serializer
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function serializeState(State $state)
    {
        try {
            return $this->serializer->serialize($state, 'json');
        } catch (ExceptionInterface $e) {
            throw new StateSerializationException($e->getMessage(), 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serializeStates(array $states)
    {
        try {
            return $this->serializer->serialize($states, 'json');
        } catch (ExceptionInterface $e) {
            throw new StateSerializationException($e->getMessage(), 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deserializeState($state, $data = null)
    {
        $json = json_decode($data, true);
        $state['data'] = $json ? $json : $data;
        $state = json_encode($state);

        try {
            return $this->serializer->deserialize(
                $state,
                'Entrili\XApiBundle\Model\State',
                'json'
            );

        } catch (ExceptionInterface $e) {
            throw new StateDeserializationException($e->getMessage(), 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deserializeStates($state, $data = null)
    {
        try {
            return $this->serializer->deserialize(
                $state,
                'Entrili\XApiBundle\Model\State[]',
                'json'
            );
        } catch (ExceptionInterface $e) {
            throw new StateDeserializationException($e->getMessage(), 0, $e);
        }
    }
}
