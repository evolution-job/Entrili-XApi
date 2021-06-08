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

use Entrili\XApiBundle\Model\Activity;
use Entrili\XApiBundle\Model\IRI;
use Entrili\XApiBundle\Model\StatementId;
use Entrili\XApiBundle\Model\StatementObject;
use Entrili\XApiBundle\Model\StatementReference;
use Entrili\XApiBundle\Model\SubStatement;

/**
 * Normalizes and denormalizes xAPI statement objects.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class ObjectNormalizer extends Normalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        if ($object instanceof Activity) {
            $activityData = array(
                'objectType' => 'Activity',
                'id' => $object->getId()->getValue(),
            );

            if (null !== $definition = $object->getDefinition()) {
                $activityData['definition'] = $this->normalizeAttribute($definition, $format, $context);
            }

            return $activityData;
        }

        if ($object instanceof StatementReference) {
            return array(
                'objectType' => 'StatementRef',
                'id' => $object->getStatementId()->getValue(),
            );
        }

        if ($object instanceof SubStatement) {
            $data = array(
                'objectType' => 'SubStatement',
                'actor' => $this->normalizeAttribute($object->getActor(), $format, $context),
                'verb' => $this->normalizeAttribute($object->getVerb(), $format, $context),
                'object' => $this->normalizeAttribute($object->getObject(), $format, $context),
            );

            if (null !== $result = $object->getResult()) {
                $data['result'] = $this->normalizeAttribute($result, $format, $context);
            }

            if (null !== $statementContext = $object->getContext()) {
                $data['context'] = $this->normalizeAttribute($statementContext, $format, $context);
            }

            return $data;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof StatementObject;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = null, array $context = array())
    {
        if (!isset($data['objectType']) || 'Activity' === $data['objectType']) {
            return $this->denormalizeActivity($data, $format, $context);
        }

        if (isset($data['objectType']) && ('Agent' === $data['objectType'] || 'Group' === $data['objectType'])) {
            return $this->denormalizeData($data, 'Entrili\XApiBundle\Model\Actor', $format, $context);
        }

        if (isset($data['objectType']) && 'SubStatement' === $data['objectType']) {
            return $this->denormalizeSubStatement($data, $format, $context);
        }

        if (isset($data['objectType']) && 'StatementRef' === $data['objectType']) {
            return new StatementReference(StatementId::fromString($data['id']));
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return 'Entrili\XApiBundle\Model\Activity' === $type || 'Entrili\XApiBundle\Model\StatementObject' === $type || 'Entrili\XApiBundle\Model\StatementReference' === $type || 'Entrili\XApiBundle\Model\SubStatement' === $type;
    }

    private function denormalizeActivity(array $data, $format = null, array $context = array())
    {
        $definition = null;

        if (isset($data['definition'])) {
            $definition = $this->denormalizeData($data['definition'], 'Entrili\XApiBundle\Model\Definition', $format, $context);
        }

        return new Activity(IRI::fromString($data['id']), $definition);
    }

    private function denormalizeSubStatement(array  $data, $format = null, array $context = array())
    {
        $actor = $this->denormalizeData($data['actor'], 'Entrili\XApiBundle\Model\Actor', $format, $context);
        $verb = $this->denormalizeData($data['verb'], 'Entrili\XApiBundle\Model\Verb', $format, $context);
        $object = $this->denormalizeData($data['object'], 'Entrili\XApiBundle\Model\StatementObject', $format, $context);
        $result = null;
        $statementContext = null;

        if (isset($data['result'])) {
            $result = $this->denormalizeData($data['result'], 'Entrili\XApiBundle\Model\Result', $format, $context);
        }

        if (isset($data['context'])) {
            $statementContext = $this->denormalizeData($data['context'], 'Entrili\XApiBundle\Model\Context', $format, $context);
        }

        return new SubStatement($actor, $verb, $object, $result, $statementContext);
    }
}
