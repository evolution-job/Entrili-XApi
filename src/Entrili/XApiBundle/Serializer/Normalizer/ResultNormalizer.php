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

use Entrili\XApiBundle\Model\Result;

/**
 * Normalizes and denormalizes xAPI statement results.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class ResultNormalizer extends Normalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        if (!$object instanceof Result) {
            return null;
        }

        $data = array();

        if (null !== $object->getScore()) {
            $data['score'] = $this->normalizeAttribute($object->getScore(), 'Entrili\XApiBundle\Model\Score', $context);
        }

        if (null !== $success = $object->getSuccess()) {
            $data['success'] = $success;
        }

        if (null !== $completion = $object->getCompletion()) {
            $data['completion'] = $completion;
        }

        if (null !== $response = $object->getResponse()) {
            $data['response'] = $response;
        }

        if (null !== $duration = $object->getDuration()) {
            $data['duration'] = $duration;
        }

        if (null !== $extensions = $object->getExtensions()) {
            $data['extensions'] = $this->normalizeAttribute($extensions, 'Entrili\XApiBundle\Model\Extensions', $context);
        }

        if (empty($data)) {
            return new \stdClass();
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Result;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = null, array $context = array())
    {
        $score = isset($data['score']) ? $this->denormalizeData($data['score'], 'Entrili\XApiBundle\Model\Score', $format, $context) : null;
        $success = isset($data['success']) ? $data['success'] : null;
        $completion = isset($data['completion']) ? $data['completion'] : null;
        $response = isset($data['response']) ? $data['response'] : null;
        $duration = isset($data['duration']) ? $data['duration'] : null;
        $extensions = isset($data['extensions']) ? $this->denormalizeData($data['extensions'], 'Entrili\XApiBundle\Model\Extensions', $format, $context) : null;

        return new Result($score, $success, $completion, $response, $duration, $extensions);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return 'Entrili\XApiBundle\Model\Result' === $type;
    }
}
