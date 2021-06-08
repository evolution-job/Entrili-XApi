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

use Entrili\XApiBundle\Model\Statement;
use Entrili\XApiBundle\Serializer\Exception\StatementDeserializationException;
use Entrili\XApiBundle\Serializer\Exception\StatementSerializationException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Serializes and deserializes {@link Statement statements} using the Symfony Serializer component.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class StatementSerializer implements StatementSerializerInterface
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
    public function serializeStatement(Statement $statement)
    {
        try {
            return $this->serializer->serialize($statement, 'json');
        } catch (ExceptionInterface $e) {
            throw new StatementSerializationException($e->getMessage(), 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serializeStatements(array $statements)
    {
        try {
            return $this->serializer->serialize($statements, 'json');
        } catch (ExceptionInterface $e) {
            throw new StatementSerializationException($e->getMessage(), 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deserializeStatement($data, array $attachments = array())
    {
        try {
            return $this->serializer->deserialize(
                $data,
                'Entrili\XApiBundle\Model\Statement',
                'json',
                array(
                    'xapi_attachments' => $attachments,
                )
            );
        } catch (ExceptionInterface $e) {
            throw new StatementDeserializationException($e->getMessage(), 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deserializeStatements($data, array $attachments = array())
    {
        try {
            return $this->serializer->deserialize(
                $data,
                'Entrili\XApiBundle\Model\Statement[]',
                'json',
                array(
                    'xapi_attachments' => $attachments,
                )
            );
        } catch (ExceptionInterface $e) {
            throw new StatementDeserializationException($e->getMessage(), 0, $e);
        }
    }
}
