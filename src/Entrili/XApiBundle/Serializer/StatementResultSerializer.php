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
use Entrili\XApiBundle\Model\StatementResult;
use Entrili\XApiBundle\Serializer\Exception\StatementResultDeserializationException;
use Entrili\XApiBundle\Serializer\Exception\StatementResultSerializationException;
use Entrili\XApiBundle\Serializer\StatementResultSerializerInterface;

/**
 * Serializes and deserializes {@link StatementResult statement results} using the Symfony Serializer component.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class StatementResultSerializer implements StatementResultSerializerInterface
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
    public function serializeStatementResult(StatementResult $statementResult)
    {
        try {
            return $this->serializer->serialize($statementResult, 'json');
        } catch (ExceptionInterface $e) {
            throw new StatementResultSerializationException($e->getMessage(), 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deserializeStatementResult($data, array $attachments = array())
    {
        try {
            return $this->serializer->deserialize(
                $data,
                'Entrili\XApiBundle\Model\StatementResult',
                'json',
                array(
                    'xapi_attachments' => $attachments,
                )
            );
        } catch (ExceptionInterface $e) {
            throw new StatementResultDeserializationException($e->getMessage(), 0, $e);
        }
    }
}
