<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Repository\Mapping;

use Doctrine\ORM\Mapping as ORM;
use Entrili\XApiBundle\Model\Activity;
use Entrili\XApiBundle\Model\IRI;
use Entrili\XApiBundle\Model\State as StateModel;

/**
 * State
 *
 * @ORM\Table(name="xapi_state")
 * @ORM\Entity(repositoryClass="Entrili\XApiBundle\Repository\StateRepository")
 */
class State
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="activityId", type="string", nullable=false)
     */
    public $activity;

    /**
     * @var \Entrili\XApiBundle\Repository\Mapping\StatementObject
     *
     * @ORM\OneToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\StatementObject", cascade={"all"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actor_id", referencedColumnName="identifier", unique=false, onDelete="CASCADE")
     * })
     */
    public $actor;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="registration_id", type="integer", nullable=false)
     */
    public $registration;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="stateId", type="string", nullable=false)
     */
    public $stateId;

    /**
     * @var string
     * @ORM\Column(name="data", type="text", nullable=true)
     */
    public $data;

    /**
     * @param StateModel $model
     * @return State
     */
    public static function fromModel(StateModel $model)
    {
        $state = new self();
        $state->activity = $model->getActivity()->getId()->getValue();
        $state->actor = StatementObject::fromModel($model->getAgent());
        $state->registration = $model->getRegistration();
        $state->stateId = $model->getStateId();
        $state->data = is_array($model->getData()) ? json_encode($model->getData()) : $model->getData();
        return $state;
    }

    /**
     * @return StateModel
     */
    public function getModel()
    {
        return new StateModel(
            new Activity(IRI::fromString($this->activity)),
            $this->actor->getModel(),
            $this->stateId,
            $this->registration,
            $this->data
        );
    }
}
