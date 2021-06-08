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

use Entrili\XApiBundle\Model\Activity;
use Entrili\XApiBundle\Serializer\Exception\ActivitySerializationException;
use Entrili\XApiBundle\Serializer\Exception\DeserializationException;
use Entrili\XApiBundle\Serializer\Exception\SerializationException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Serializes {@link Activity activities} using the Symfony Serializer component.
 *
 * @author Jérôme Parmentier <jerome.parmentier@acensi.fr>
 */
final class ActivitySerializer implements ActivitySerializerInterface
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function deserializeActivity($data, array $attachments = array())
    {
        try {
            return $this->serializer->deserialize(
                $data,
                'Entrili\XApiBundle\Model\Activity',
                'json',
                array(
                    'xapi_attachments' => $attachments,
                )
            );
        } catch (DeserializationException $e) {
            throw new ActivitySerializationException($e->getMessage(), 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serializeActivity(Activity $activity)
    {
        try {
            return $this->serializer->serialize($activity, 'json');
        } catch (SerializationException $e) {
            throw new ActivitySerializationException($e->getMessage(), 0, $e);
        }
    }
}
