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
 * An activity provider's state stored on a remote LRS.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class State
{
    /**
     * @var Activity The associated activity
     */
    private $activity;

    /**
     * @var Actor|Agent The associated actor
     */
    private $actor;

    /**
     * @var int
     */
    private $registration;

    /**
     * @var string The state id
     */
    private $stateId;

    /**
     * @var string The state info
     */
    private $data;

    public function __construct(Activity $activity, Actor $actor, $stateId, $registration = null, $data = null)
    {
        if (!$actor instanceof Agent) {
            @trigger_error(sprintf('Passing an instance of "%s" as the second argument is deprecated since 1.2. In 3.0, only instances of "Entrili\XApiBundle\Model\Agent" will be accepted.', get_class($actor)), E_USER_DEPRECATED);
        }

        $this->activity = $activity;
        $this->actor = $actor;
        $this->stateId = $stateId;
        $this->registration = $registration;
        $this->data = $data;
    }

    /**
     * Returns the activity.
     *
     * @return Activity The activity
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Returns the actor.
     *
     * @return Actor The actor
     *
     * @deprecated since 1.2, to be removed in 3.0
     */
    public function getActor()
    {
        @trigger_error(sprintf('The "%s()" method is deprecated since 1.2 and will be removed in 3.0, use "%s::getAgent()" instead.', __METHOD__, __CLASS__), E_USER_DEPRECATED);

        return $this->getAgent();
    }

    /**
     * Returns the agent.
     *
     * @return Actor The agent
     */
    public function getAgent()
    {
        return $this->actor;
    }

    /**
     * Returns the registration id.
     *
     * @return int
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * @param int $registration
     * @return $this
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;
        return $this;
    }

    /**
     * Returns the state's id.
     *
     * @return string The id
     */
    public function getStateId()
    {
        return $this->stateId;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }
}
