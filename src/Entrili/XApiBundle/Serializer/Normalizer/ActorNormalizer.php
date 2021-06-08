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

use Entrili\XApiBundle\Model\Actor;
use Entrili\XApiBundle\Model\Agent;
use Entrili\XApiBundle\Model\Group;
use Entrili\XApiBundle\Model\InverseFunctionalIdentifier;
use Entrili\XApiBundle\Model\IRI;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

/**
 * Normalizes and denormalizes xAPI statement actors.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class ActorNormalizer extends Normalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        if (!$object instanceof Actor) {
            return null;
        }

        $data = array();

        $this->normalizeInverseFunctionalIdentifier($object->getInverseFunctionalIdentifier(), $data, $format, $context);

        if (null !== $name = $object->getName()) {
            $data['name'] = $name;
        }

        if ($object instanceof Group) {
            $members = array();

            foreach ($object->getMembers() as $member) {
                $members[] = $this->normalize($member);
            }

            if (count($members) > 0) {
                $data['member'] = $members;
            }

            $data['objectType'] = 'Group';
        } else {
            $data['objectType'] = 'Agent';
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Actor;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = null, array $context = array())
    {
        $inverseFunctionalIdentifier = $this->denormalizeInverseFunctionalIdentifier($data, $format, $context);
        $name = isset($data['name']) ? $data['name'] : null;

        if (isset($data['objectType']) && 'Group' === $data['objectType']) {
            return $this->denormalizeGroup($inverseFunctionalIdentifier, $name, $data, $format, $context);
        }

        if (null === $inverseFunctionalIdentifier) {
            throw new InvalidArgumentException('Missing inverse functional identifier for agent.');
        }

        return new Agent($inverseFunctionalIdentifier, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return 'Entrili\XApiBundle\Model\Actor' === $type || 'Entrili\XApiBundle\Model\Agent' === $type || 'Entrili\XApiBundle\Model\Group' === $type;
    }

    private function normalizeInverseFunctionalIdentifier(InverseFunctionalIdentifier $iri = null, &$data, $format = null, array $context = array())
    {
        if (null === $iri) {
            return;
        }

        if (null !== $mbox = $iri->getMbox()) {
            $data['mbox'] = $mbox->getValue();
        }

        if (null !== $mboxSha1Sum = $iri->getMboxSha1Sum()) {
            $data['mbox_sha1sum'] = $mboxSha1Sum;
        }

        if (null !== $openId = $iri->getOpenId()) {
            $data['openid'] = $openId;
        }

        if (null !== $account = $iri->getAccount()) {
            $data['account'] = $this->normalizeAttribute($account, $format, $context);
        }
    }

    private function denormalizeInverseFunctionalIdentifier($data, $format = null, array $context = array())
    {
        if (isset($data['mbox'])) {
            return InverseFunctionalIdentifier::withMbox(IRI::fromString($data['mbox']));
        }

        if (isset($data['mbox_sha1sum'])) {
            return InverseFunctionalIdentifier::withMboxSha1Sum($data['mbox_sha1sum']);
        }

        if (isset($data['openid'])) {
            return InverseFunctionalIdentifier::withOpenId($data['openid']);
        }

        if (isset($data['account'])) {
            return InverseFunctionalIdentifier::withAccount($this->denormalizeAccount($data, $format, $context));
        }
    }

    private function denormalizeAccount($data, $format = null, array $context = array())
    {
        if (!isset($data['account'])) {
            return null;
        }

        return $this->denormalizeData($data['account'], 'Entrili\XApiBundle\Model\Account', $format, $context);
    }

    private function denormalizeGroup(InverseFunctionalIdentifier $iri = null, $name, $data, $format = null, array $context = array())
    {
        $members = array();

        if (isset($data['member'])) {
            foreach ($data['member'] as $member) {
                $members[] = $this->denormalize($member, 'Entrili\XApiBundle\Model\Agent', $format, $context);
            }
        }

        return new Group($iri, $name, $members);
    }
}
