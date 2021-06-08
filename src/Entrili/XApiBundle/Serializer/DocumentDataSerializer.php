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
use Entrili\XApiBundle\Model\DocumentData;
use Entrili\XApiBundle\Serializer\DocumentDataSerializerInterface;
use Entrili\XApiBundle\Serializer\Exception\DocumentDataDeserializationException;
use Entrili\XApiBundle\Serializer\Exception\DocumentDataSerializationException;

/**
 * Serializes and deserializes {@link Document documents} using the Symfony Serializer component.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class DocumentDataSerializer implements DocumentDataSerializerInterface
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
    public function serializeDocumentData(DocumentData $data)
    {
        try {
            return $this->serializer->serialize($data, 'json');
        } catch (ExceptionInterface $e) {
            throw new DocumentDataSerializationException($e->getMessage(), 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deserializeDocumentData($data)
    {
        try {
            return $this->serializer->deserialize($data, 'Entrili\XApiBundle\Model\DocumentData', 'json');
        } catch (ExceptionInterface $e) {
            throw new DocumentDataDeserializationException($e->getMessage(), 0, $e);
        }
    }
}
