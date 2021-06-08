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

/**
 * Serialize {@link Activity activities}.
 *
 * @author Jérôme Parmentier <jerome.parmentier@acensi.fr>
 */
interface ActivitySerializerInterface
{
    /**
     * Serializes an activity into a JSON encoded string.
     *
     * @param Activity $activity The activity to serialize
     *
     * @throws ActivitySerializationException When the serialization fails
     *
     * @return string The serialized activity
     */
    public function serializeActivity(Activity $activity);

    public function deserializeActivity($data, array $attachments = array());
}
