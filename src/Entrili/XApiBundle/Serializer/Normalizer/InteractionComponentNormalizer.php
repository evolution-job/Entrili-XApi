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

use Entrili\XApiBundle\Model\Interaction\InteractionComponent;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Denormalizes xAPI statement activity {@link InteractionComponent interaction components}.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class InteractionComponentNormalizer extends Normalizer implements DenormalizerInterface, NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        if (!$object instanceof InteractionComponent) {
            return;
        }

        $data = array(
            'id' => $object->getId(),
        );

        if (null !== $description = $object->getDescription()) {
            $data['description'] = $this->normalizeAttribute($description, $format, $context);
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof InteractionComponent;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = null, array $context = array())
    {
        $description = null;

        if (isset($data['description'])) {
            $description = $this->denormalizeData($data['description'], 'Entrili\XApiBundle\Model\LanguageMap', $format, $context);
        }

        return new InteractionComponent($data['id'], $description);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return 'Entrili\XApiBundle\Model\Interaction\InteractionComponent' === $type;
    }
}
