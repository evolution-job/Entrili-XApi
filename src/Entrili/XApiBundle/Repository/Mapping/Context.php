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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Entrili\XApiBundle\Model\Context as ContextModel;
use Entrili\XApiBundle\Model\ContextActivities;

/**
 * Context
 *
 * @ORM\Table(name="xapi_context")
 * @ORM\Entity
 */
class Context
{
    /**
     * @var bool|null
     *
     * @ORM\Column(name="hasContextActivities", type="boolean", nullable=true)
     */
    public $hasContextActivities;

    /**
     * @var string
     *
     * @ORM\Column(name="revision", type="string", nullable=true)
     */
    public $revision;

    /**
     * @var string
     *
     * @ORM\Column(name="platform", type="string", nullable=true)
     */
    public $platform;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", nullable=true)
     */
    public $language;

    /**
     * @var integer
     *
     * @ORM\Column(name="identifier", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $identifier;

    /**
     * @var \Entrili\XApiBundle\Repository\Mapping\StatementObject
     *
     * @ORM\OneToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\StatementObject", cascade={"all"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="instructor_id", referencedColumnName="identifier", unique=true)
     * })
     */
    public $instructor;

    /**
     * @var \Entrili\XApiBundle\Repository\Mapping\StatementObject
     *
     * @ORM\OneToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\StatementObject", cascade={"all"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_id", referencedColumnName="identifier", unique=true)
     * })
     */
    public $team;

    /**
     * @var \Entrili\XApiBundle\Repository\Mapping\Extensions
     *
     * @ORM\OneToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\Extensions", cascade={"all"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="extensions_id", referencedColumnName="identifier", unique=true)
     * })
     */
    public $extensions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entrili\XApiBundle\Repository\Mapping\StatementObject", mappedBy="parentContext", cascade={"all"})
     */
    public $parentActivities;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entrili\XApiBundle\Repository\Mapping\StatementObject", mappedBy="groupingContext", cascade={"all"})
     */
    public $groupingActivities;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entrili\XApiBundle\Repository\Mapping\StatementObject", mappedBy="categoryContext", cascade={"all"})
     */
    public $categoryActivities;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entrili\XApiBundle\Repository\Mapping\StatementObject", mappedBy="otherContext", cascade={"all"})
     */
    public $otherActivities;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entrili\XApiBundle\Repository\Mapping\Statement", mappedBy="context", cascade={"all"})
     */
    public $statements;

    /**
     * @var int
     *
     * @ORM\Column(name="registration_id", type="integer", nullable=false)
     */
    public $registration;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parentActivities = new ArrayCollection();
        $this->groupingActivities = new ArrayCollection();
        $this->categoryActivities = new ArrayCollection();
        $this->otherActivities = new ArrayCollection();
        $this->statements = new ArrayCollection();
    }

    /**
     * @param ContextModel $model
     * @return Context
     */
    public static function fromModel(ContextModel $model)
    {
        $context = new self();
        $context->registration = $model->getRegistration();
        $context->revision = $model->getRevision();
        $context->platform = $model->getPlatform();
        $context->language = $model->getLanguage();

        if (null !== $instructor = $model->getInstructor()) {
            $context->instructor = StatementObject::fromModel($instructor);
        }

        if (null !== $team = $model->getTeam()) {
            $context->team = StatementObject::fromModel($team);
        }

        if (null !== $contextActivities = $model->getContextActivities()) {
            $context->hasContextActivities = true;

            if (null !== $parentActivities = $contextActivities->getParentActivities()) {
                $context->parentActivities = array();

                foreach ($parentActivities as $parentActivity) {
                    $activity = StatementObject::fromModel($parentActivity);
                    $activity->parentContext = $context;
                    $context->parentActivities[] = $activity;
                }
            }

            if (null !== $groupingActivities = $contextActivities->getGroupingActivities()) {
                $context->groupingActivities = array();

                foreach ($groupingActivities as $groupingActivity) {
                    $activity = StatementObject::fromModel($groupingActivity);
                    $activity->groupingContext = $context;
                    $context->groupingActivities[] = $activity;
                }
            }

            if (null !== $categoryActivities = $contextActivities->getCategoryActivities()) {
                $context->categoryActivities = array();

                foreach ($categoryActivities as $categoryActivity) {
                    $activity = StatementObject::fromModel($categoryActivity);
                    $activity->categoryContext = $context;
                    $context->categoryActivities[] = $activity;
                }
            }

            if (null !== $otherActivities = $contextActivities->getOtherActivities()) {
                $context->otherActivities = array();

                foreach ($otherActivities as $otherActivity) {
                    $activity = StatementObject::fromModel($otherActivity);
                    $activity->otherContext = $context;
                    $context->otherActivities[] = $activity;
                }
            }
        } else {
            $context->hasContextActivities = false;
        }

        if (null !== $contextExtensions = $model->getExtensions()) {
            $context->extensions = Extensions::fromModel($contextExtensions);
        }

        return $context;
    }

    /**
     * @return ContextModel
     */
    public function getModel()
    {
        $context = new ContextModel();
        $context = $context->withRegistration($this->registration);
        $context = $context->withRevision($this->revision);
        $context = $context->withPlatform($this->platform);
        $context = $context->withLanguage($this->language);

        if (null !== $this->instructor) {
            $context = $context->withInstructor($this->instructor->getModel());
        }

        if (null !== $this->team) {
            $context = $context->withTeam($this->team->getModel());
        }

        if ($this->hasContextActivities) {
            $contextActivities = new ContextActivities();

            if (null !== $this->parentActivities) {
                foreach ($this->parentActivities as $contextParentActivity) {
                    $contextActivities = $contextActivities->withAddedParentActivity($contextParentActivity->getModel());
                }
            }

            if (null !== $this->groupingActivities) {
                foreach ($this->groupingActivities as $contextGroupingActivity) {
                    $contextActivities = $contextActivities->withAddedGroupingActivity($contextGroupingActivity->getModel());
                }
            }

            if (null !== $this->categoryActivities) {
                foreach ($this->categoryActivities as $contextCategoryActivity) {
                    $contextActivities = $contextActivities->withAddedCategoryActivity($contextCategoryActivity->getModel());
                }
            }

            if (null !== $this->otherActivities) {
                foreach ($this->otherActivities as $contextOtherActivity) {
                    $contextActivities = $contextActivities->withAddedOtherActivity($contextOtherActivity->getModel());
                }
            }

            $context = $context->withContextActivities($contextActivities);
        }

        if (null !== $this->extensions) {
            $context = $context->withExtensions($this->extensions->getModel());
        }

        return $context;
    }
}
