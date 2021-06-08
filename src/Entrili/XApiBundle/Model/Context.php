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
 * Contextual information for an xAPI statement.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class Context
{
    /**
     * @var int|null
     */
    private $registration;

    /**
     * @var Actor|null
     */
    private $instructor;

    /**
     * @var Group|null
     */
    private $team;

    /**
     * @var ContextActivities|null
     */
    private $contextActivities;
    /**
     * @var string|null
     */
    private $revision;
    /**
     * @var string|null
     */
    private $platform;

    /**
     * @var string|null
     */
    private $language;

    /**
     * @var array|null
     */
    private $statements;

    /**
     * @var Extensions|null
     */
    private $extensions;

    /**
     * Context constructor.
     * @param int|null $registration
     */
    public function __construct($registration = null)
    {
        $this->registration = $registration;
    }

    /**
     * @param string $registration
     *
     * @return self
     */
    public function withRegistration($registration)
    {
        $context = clone $this;
        $context->registration = $registration;

        return $context;
    }

    public function withInstructor(Actor $instructor)
    {
        $context = clone $this;
        $context->instructor = $instructor;

        return $context;
    }

    public function withTeam(Group $team)
    {
        $context = clone $this;
        $context->team = $team;

        return $context;
    }

    public function withContextActivities(ContextActivities $contextActivities)
    {
        $context = clone $this;
        $context->contextActivities = $contextActivities;

        return $context;
    }

    /**
     * @param string $revision
     *
     * @return self
     */
    public function withRevision($revision)
    {
        $context = clone $this;
        $context->revision = $revision;

        return $context;
    }

    /**
     * @param string $platform
     *
     * @return self
     */
    public function withPlatform($platform)
    {
        $context = clone $this;
        $context->platform = $platform;

        return $context;
    }

    /**
     * @param string $language
     *
     * @return self
     */
    public function withLanguage($language)
    {
        $context = clone $this;
        $context->language = $language;

        return $context;
    }

    public function withStatement($statement)
    {
        $context = clone $this;
        if (is_array($statement))
            $context->statements = $statement;
        else
            $context->statements[] = $statement;

        return $context;
    }

    public function withExtensions(Extensions $extensions)
    {
        $context = clone $this;
        $context->extensions = $extensions;

        return $context;
    }

    /**
     * @return ModuleRead|int|null
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * @param int|null $registration
     * @return $this
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;

        return $this;
    }

    /**
     * @return Actor|null
     */
    public function getInstructor()
    {
        return $this->instructor;
    }

    /**
     * @return Group|null
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @return ContextActivities|null
     */
    public function getContextActivities()
    {
        return $this->contextActivities;
    }

    /**
     * @return string|null
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * @return string|null
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * @return string|null
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return array|null
     */
    public function getStatements()
    {
        return $this->statements;
    }

    /**
     * @return Extensions|null
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    public function equals(Context $context)
    {
        if ($this->registration !== $context->registration) {
            return false;
        }

        if (null !== $this->instructor xor null !== $context->instructor) {
            return false;
        }

        if (null !== $this->instructor && null !== $context->instructor && !$this->instructor->equals($context->instructor)) {
            return false;
        }

        if (null !== $this->team xor null !== $context->team) {
            return false;
        }

        if (null !== $this->team && null !== $context->team && !$this->team->equals($context->team)) {
            return false;
        }

        if ($this->contextActivities != $context->contextActivities) {
            return false;
        }

        if ($this->revision !== $context->revision) {
            return false;
        }

        if ($this->platform !== $context->platform) {
            return false;
        }

        if ($this->language !== $context->language) {
            return false;
        }

        if (null !== $this->statement xor null !== $context->statement) {
            return false;
        }

        if (null !== $this->statement && null !== $context->statement && !$this->statement->equals($context->statement)) {
            return false;
        }

        if (null !== $this->extensions xor null !== $context->extensions) {
            return false;
        }

        if (null !== $this->extensions && null !== $context->extensions && !$this->extensions->equals($context->extensions)) {
            return false;
        }

        return true;
    }
}
