<?php

namespace Entrili\XApiBundle\Serializer\Normalizer;

use Entrili\XApiBundle\Model\State;

final class StateNormalizer extends Normalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {

        if (!$object instanceof State) {
            return null;
        }

        $array = [];

        if (null !== $activity = $object->getActivity()->getId()->getValue()) {
            $array['activityId'] = $this->normalizeAttribute($activity, $format, $context);
        }

        if (null !== $agent = $object->getAgent()) {
            $array['agent'] = $this->normalizeAttribute($agent, $format, $context);
        }

        if (null !== $stateId = $object->getStateId()) {
            $array['stateId'] = $this->normalizeAttribute($stateId, $format, $context);
        }

        if (null !== $registration = $object->getRegistration()) {
            $array['registration'] = $this->normalizeAttribute($registration, $format, $context);
        }

        if (null !== $data = $object->getData()) {
            $array['data'] = $this->normalizeAttribute($data, $format, $context);
        }

        return $array;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof State;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = null, array $context = array())
    {
        /**
         * set of States
         */
        if (isset($data[0])) {
            $stateIds = [];
            foreach ($data as $d) {
                $stateIds[] = $this->denormarlizeState($d, $format);
            }
            return $stateIds;
        }

        /**
         * Once
         */
        return $this->denormarlizeState($data, $format);
    }

    /**
     * @param $
     * @param null|string $format
     * @param null|string $data
     * @return State
     */
    public function denormarlizeState($state, $format = null)
    {

        $activity = null;
        if (isset($state['activityId'])) {
            $activity = $this->denormalizeData(['id' => $state['activityId']], 'Entrili\XApiBundle\Model\Activity', $format);
        }

        $agent = null;
        if (isset($state['agent'])) {
            $agent = $this->denormalizeData(json_decode($state['agent'], true), 'Entrili\XApiBundle\Model\Agent', $format);
        }

        $stateId = null;
        if (isset($state['stateId'])) {
            $stateId = $state['stateId'];
        }

        $registration = null;
        if (isset($state['registration'])) {
            $registration = $state['registration'];
        }

        if (!is_array($state['data']) && !is_null(json_decode($state['data'], true)))
            $data = json_decode($state['data'], true);
        else
            $data = $state['data'];

        return new State($activity, $agent, $stateId, $registration, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return 'Entrili\XApiBundle\Model\State' === $type;
    }
}
