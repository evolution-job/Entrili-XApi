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

use Symfony\Component\Serializer\SerializerInterface;

/**
 * Creates serializer instances that use the Symfony Serializer component.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class SerializerFactory implements SerializerFactoryInterface
{
    private $serializer;

    public function __construct(SerializerInterface $serializer = null)
    {
        $this->serializer = $serializer ?: Serializer::createSerializer();
    }

    /**
     * {@inheritdoc}
     */
    public function createActivitySerializer()
    {
        return new ActivitySerializer($this->serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function createActorSerializer()
    {
        return new ActorSerializer($this->serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function createDocumentDataSerializer()
    {
        return new DocumentDataSerializer($this->serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function createPersonSerializer()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function createStateSerializer()
    {
        return new StateSerializer($this->serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function createStatementSerializer()
    {
        return new StatementSerializer($this->serializer);
    }

    /**
     * {@inheritdoc}
     */
    public function createStatementResultSerializer()
    {
        return new StatementResultSerializer($this->serializer);
    }
}
