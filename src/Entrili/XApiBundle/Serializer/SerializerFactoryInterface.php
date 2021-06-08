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

/**
 * Handles the creation of serializer objects.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface SerializerFactoryInterface
{
    /**
     * Creates an activity serializer.
     *
     * @return ActivitySerializerInterface
     */
    public function createActivitySerializer();

    /**
     * Creates an actor serializer.
     *
     * @return ActorSerializerInterface
     */
    public function createActorSerializer();

    /**
     * Creates a document data serializer.
     *
     * @return DocumentDataSerializerInterface
     */
    public function createDocumentDataSerializer();

    /**
     * Creates a person serializer.
     *
     * @return PersonSerializerInterface
     */
    public function createPersonSerializer();

    /**
     * Creates a state serializer.
     *
     * @return StateSerializerInterface
     */
    public function createStateSerializer();

    /**
     * Creates a statement serializer.
     *
     * @return StatementSerializerInterface
     */
    public function createStatementSerializer();

    /**
     * Creates a statement result serializer.
     *
     * @return StatementResultSerializerInterface
     */
    public function createStatementResultSerializer();
}
