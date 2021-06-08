<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Serializer\Normalizer;

use Entrili\XApiBundle\Exception\UnsupportedStatementVersionException;
use Entrili\XApiBundle\Model\Statement;
use Entrili\XApiBundle\Model\StatementId;

/**
 * Normalizes and denormalizes xAPI statements.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class StatementNormalizer extends Normalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        if (!$object instanceof Statement) {
            return null;
        }

        $data = array(
            'actor' => $this->normalizeAttribute($object->getActor(), $format, $context),
            'verb' => $this->normalizeAttribute($object->getVerb(), $format, $context),
            'object' => $this->normalizeAttribute($object->getObject(), $format, $context),
            'version' => $object->getVersion(),
        );

        if (null !== $id = $object->getId()) {
            $data['id'] = $id->getValue();
        }

        if (null !== $authority = $object->getAuthority()) {
            $data['authority'] = $this->normalizeAttribute($authority, $format, $context);
        }

        if (null !== $result = $object->getResult()) {
            $data['result'] = $this->normalizeAttribute($result, $format, $context);
        }

        if (null !== $result = $object->getCreated()) {
            $data['timestamp'] = $this->normalizeAttribute($result, $format, $context);
        }

        if (null !== $result = $object->getStored()) {
            $data['stored'] = $this->normalizeAttribute($result, $format, $context);
        }

        if (null !== $object->getContext()) {
            $data['context'] = $this->normalizeAttribute($object->getContext(), $format, $context);
        }

        if (null !== $attachments = $object->getAttachments()) {
            $data['attachments'] = $this->normalizeAttribute($attachments, $format, $context);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Statement;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = null, array $context = array())
    {
        /**
         * set of Statements
         */
        if (isset($data[0])) {
            $statementIds = [];
            foreach ($data as $d) {
                $statementIds[] = $this->denormarlizeStatement($d, $format, $context);
            }
            return $statementIds;
        }

        /**
         * Once
         */
        return $this->denormarlizeStatement($data, $format, $context);
    }

    /**
     * @param array $data
     * @param null|string $format
     * @param array $context
     * @return Statement
     * @throws UnsupportedStatementVersionException
     */
    public function denormarlizeStatement($data, $format = null, array $context = array())
    {
        $version = null;
        if (isset($data['version'])) {
            $version = $data['version'];

            if (!preg_match('/^1\.0(?:\.\d+)?$/', $version)) {
                throw new UnsupportedStatementVersionException(sprintf('Statements at version "%s" are not supported.', $version));
            }
        }

        $id = isset($data['id']) ? StatementId::fromString($data['id']) : null;
        $actor = $this->denormalizeData($data['actor'], 'Entrili\XApiBundle\Model\Actor', $format, $context);
        $verb = $this->denormalizeData($data['verb'], 'Entrili\XApiBundle\Model\Verb', $format, $context);
        $object = $this->denormalizeData($data['object'], 'Entrili\XApiBundle\Model\StatementObject', $format, $context);
        $result = null;
        $authority = null;
        $created = null;
        $stored = null;
        $statementContext = null;
        $attachments = null;

        if (isset($data['result'])) {
            $result = $this->denormalizeData($data['result'], 'Entrili\XApiBundle\Model\Result', $format, $context);
        }

        if (isset($data['authority'])) {
            $authority = $this->denormalizeData($data['authority'], 'Entrili\XApiBundle\Model\Actor', $format, $context);
        }

        if (isset($data['timestamp'])) {
            $created = $this->denormalizeData($data['timestamp'], 'DateTime', $format, $context);
        }

        if (isset($data['stored'])) {
            $stored = $this->denormalizeData($data['stored'], 'DateTime', $format, $context);
        }

        if (isset($data['context'])) {
            $statementContext = $this->denormalizeData($data['context'], 'Entrili\XApiBundle\Model\Context', $format, $context);
        }

        if (isset($data['attachments'])) {
            $attachments = $this->denormalizeData($data['attachments'], 'Entrili\XApiBundle\Model\Attachment[]', $format, $context);
        }

        return new Statement($id, $actor, $verb, $object, $result, $authority, $created, $stored, $statementContext, $attachments, $version);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return 'Entrili\XApiBundle\Model\Statement' === $type;
    }
}
