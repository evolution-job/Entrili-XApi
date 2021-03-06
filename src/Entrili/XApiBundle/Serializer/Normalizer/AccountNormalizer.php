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

use Entrili\XApiBundle\Model\Account;
use Entrili\XApiBundle\Model\IRL;

/**
 * Normalizes and denormalizes xAPI statement accounts.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class AccountNormalizer extends Normalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        if (!$object instanceof Account) {
            return null;
        }

        return array(
            'name' => $object->getName(),
            'homePage' => $object->getHomePage()->getValue(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Account;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = null, array $context = array())
    {
        $name = '';
        $homePage = '';

        if (isset($data['name'])) {
            $name = $data['name'];
        }

        if (isset($data['homePage'])) {
            $homePage = $data['homePage'];
        }

        return new Account($name, IRL::fromString($homePage));
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return 'Entrili\XApiBundle\Model\Account' === $type;
    }
}
