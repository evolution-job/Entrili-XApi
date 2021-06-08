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

use Entrili\XApiBundle\Serializer\Normalizer\AccountNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\ActorNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\AttachmentNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\ContextActivitiesNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\ContextNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\DefinitionNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\DocumentDataNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\ExtensionsNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\FilterNullValueNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\InteractionComponentNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\LanguageMapNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\ObjectNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\ResultNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\StatementNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\StatementResultNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\StateNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\TimestampNormalizer;
use Entrili\XApiBundle\Serializer\Normalizer\VerbNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Entry point to set up the {@link \Symfony\Component\Serializer\Serializer Symfony Serializer component}
 * for the Experience API.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Serializer
{
    /**
     * Creates a new Serializer.
     *
     * @return SerializerInterface The Serializer
     */
    public static function createSerializer()
    {
        $normalizers = array(
            new AccountNormalizer(),
            new ActorNormalizer(),
            new AttachmentNormalizer(),
            new ContextNormalizer(),
            new ContextActivitiesNormalizer(),
            new DefinitionNormalizer(),
            new DocumentDataNormalizer(),
            new ExtensionsNormalizer(),
            new InteractionComponentNormalizer(),
            new LanguageMapNormalizer(),
            new ObjectNormalizer(),
            new ResultNormalizer(),
            new StateNormalizer(),
            new StatementNormalizer(),
            new StatementResultNormalizer(),
            new TimestampNormalizer(),
            new VerbNormalizer(),
            new ArrayDenormalizer(),
            new FilterNullValueNormalizer(),
            new PropertyNormalizer(),
        );
        $encoders = array(
            new JsonEncoder(),
        );

        return new SymfonySerializer($normalizers, $encoders);
    }
}
