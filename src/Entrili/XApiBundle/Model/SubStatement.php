<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Model;

/**
 * A {@link Statement} included as part of a parent Statement.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class SubStatement extends StatementObject
{
    /**
     * @var Verb $verb The {@link Verb}
     */
    private $verb;

    /**
     * @var Actor The {@link Actor}
     */
    private $actor;

    /**
     * @var StatementObject The {@link StatementObject}
     */
    private $object;

    /**
     * @var Result The {@link Activity} {@link Result}
     */
    private $result;

    /**
     * @var \DateTime The timestamp of when the events described in this statement occurred
     */
    private $created;

    /**
     * @var Context The {@link Statement} {@link Context}
     */
    private $context;

    private $attachments;

    public function __construct(Actor $actor, Verb $verb, StatementObject $object, Result $result = null, Context $context = null, \DateTime $created = null, array $attachments = null)
    {
        if ($object instanceof SubStatement) {
            throw new \InvalidArgumentException('Nesting sub statements is forbidden by the xAPI spec.');
        }

        $this->actor = $actor;
        $this->verb = $verb;
        $this->object = $object;
        $this->result = $result;
        $this->created = $created;
        $this->context = $context;
        $this->attachments = null !== $attachments ? array_values($attachments) : null;
    }

    public function withActor(Actor $actor)
    {
        $subStatement = clone $this;
        $subStatement->actor = $actor;

        return $subStatement;
    }

    public function withVerb(Verb $verb)
    {
        $subStatement = clone $this;
        $subStatement->verb = $verb;

        return $subStatement;
    }

    public function withObject(StatementObject $object)
    {
        $subStatement = clone $this;
        $subStatement->object = $object;

        return $subStatement;
    }

    public function withResult(Result $result)
    {
        $subStatement = clone $this;
        $subStatement->result = $result;

        return $subStatement;
    }

    public function withCreated(\DateTime $created = null)
    {
        $statement = clone $this;
        $statement->created = $created;

        return $statement;
    }

    public function withContext(Context $context)
    {
        $subStatement = clone $this;
        $subStatement->context = $context;

        return $subStatement;
    }

    /**
     * @param Attachment[]|null $attachments
     *
     * @return self
     */
    public function withAttachments(array $attachments = null)
    {
        $statement = clone $this;
        $statement->attachments = null !== $attachments ? array_values($attachments) : null;

        return $statement;
    }

    /**
     * Returns the Statement's {@link Verb}.
     *
     * @return Verb The Verb
     */
    public function getVerb()
    {
        return $this->verb;
    }

    /**
     * Returns the Statement's {@link Actor}.
     *
     * @return Actor The Actor
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * Returns the Statement's {@link StatementObject}.
     *
     * @return \Entrili\XApiBundle\Model\StatementObject The StatementObject
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Returns the {@link Activity} {@link Result}.
     *
     * @return Result The Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Returns the timestamp of when the events described in this statement
     * occurred.
     *
     * @return \DateTime The timestamp
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Returns the {@link Statement} {@link Context}.
     *
     * @return Context The Context
     */
    public function getContext()
    {
        return $this->context;
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Tests whether or not this Statement is a void Statement (i.e. it voids
     * another Statement).
     *
     * @return bool True if the Statement voids another Statement, false otherwise
     */
    public function isVoidStatement()
    {
        return $this->verb->isVoidVerb();
    }

    /**
     * {@inheritdoc}
     */
    public function equals($object)
    {
        if ('Entrili\XApiBundle\Model\SubStatement' !== get_class($object)) {
            return false;
        }

        /** @var SubStatement $object */

        if (!$this->actor->equals($object->actor)) {
            return false;
        }

        if (!$this->verb->equals($object->verb)) {
            return false;
        }

        if (!$this->object->equals($object->object)) {
            return false;
        }

        if (null === $this->result && null !== $object->result) {
            return false;
        }

        if (null !== $this->result && null === $object->result) {
            return false;
        }

        if (null !== $this->result && !$this->result->equals($object->result)) {
            return false;
        }

        if ($this->created != $object->created) {
            return false;
        }

        if (null !== $this->context xor null !== $object->context) {
            return false;
        }

        if (null !== $this->context && null !== $object->context && !$this->context->equals($object->context)) {
            return false;
        }

        if (null !== $this->attachments xor null !== $object->attachments) {
            return false;
        }

        if (null !== $this->attachments && null !== $object->attachments) {
            if (count($this->attachments) !== count($object->attachments)) {
                return false;
            }

            foreach ($this->attachments as $key => $attachment) {
                if (!$attachment->equals($object->attachments[$key])) {
                    return false;
                }
            }
        }

        return true;
    }
}
