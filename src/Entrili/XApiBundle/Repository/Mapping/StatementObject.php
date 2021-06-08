<?php

namespace Entrili\XApiBundle\Repository\Mapping;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Entrili\XApiBundle\Model\Account;
use Entrili\XApiBundle\Model\Activity;
use Entrili\XApiBundle\Model\Actor as ActorModel;
use Entrili\XApiBundle\Model\Agent;
use Entrili\XApiBundle\Model\Definition;
use Entrili\XApiBundle\Model\Group;
use Entrili\XApiBundle\Model\InverseFunctionalIdentifier;
use Entrili\XApiBundle\Model\IRI;
use Entrili\XApiBundle\Model\IRL;
use Entrili\XApiBundle\Model\LanguageMap;
use Entrili\XApiBundle\Model\StatementId;
use Entrili\XApiBundle\Model\StatementObject as ObjectModel;
use Entrili\XApiBundle\Model\StatementReference;
use Entrili\XApiBundle\Model\SubStatement;

/**
 * StatementObject
 *
 * @ORM\Table(name="xapi_object", indexes={
 *     @ORM\Index(name="statement_index", columns={"hash"})
 * })
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class StatementObject
{
    const TYPE_ACTIVITY = 'activity';
    const TYPE_AGENT = 'agent';
    const TYPE_GROUP = 'group';
    const TYPE_STATEMENT_REFERENCE = 'statement_reference';
    const TYPE_SUB_STATEMENT = 'sub_statement';

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    public $type;

    /**
     * @var string
     *
     * @ORM\Column(name="activityId", type="string", nullable=true)
     */
    public $activityId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="hasActivityDefinition", type="boolean", nullable=true)
     */
    public $hasActivityDefinition;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="hasActivityName", type="boolean", nullable=true)
     */
    public $hasActivityName;

    /**
     * @var array
     *
     * @ORM\Column(name="activityName", type="json", nullable=true)
     */
    public $activityName;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="hasActivityDescription", type="boolean", nullable=true)
     */
    public $hasActivityDescription;

    /**
     * @var array|null
     *
     * @ORM\Column(name="activityDescription", type="json", nullable=true)
     */
    public $activityDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="activityType", type="string", nullable=true)
     */
    public $activityType;

    /**
     * @var string
     *
     * @ORM\Column(name="activityMoreInfo", type="string", nullable=true)
     */
    public $activityMoreInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="mbox", type="string", nullable=true)
     */
    public $mbox;

    /**
     * @var string
     *
     * @ORM\Column(name="mboxSha1Sum", type="string", nullable=true)
     */
    public $mboxSha1Sum;

    /**
     * @var string
     *
     * @ORM\Column(name="openId", type="string", nullable=true)
     */
    public $openId;

    /**
     * @var string
     *
     * @ORM\Column(name="accountName", type="string", nullable=true)
     */
    public $accountName;

    /**
     * @var string
     *
     * @ORM\Column(name="accountHomePage", type="string", nullable=true)
     */
    public $accountHomePage;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    public $name;

    /**
     * @var string
     *
     * @ORM\Column(name="referencedStatementId", type="string", nullable=true)
     */
    public $referencedStatementId;

    /**
     * @var integer
     *
     * @ORM\Column(name="identifier", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $identifier;

    /**
     * @var StatementObject
     *
     * @ORM\OneToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\StatementObject", cascade={"all"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actor_id", referencedColumnName="identifier", unique=true, onDelete="CASCADE")
     * })
     */
    public $actor;

    /**
     * @var Verb
     *
     * @ORM\OneToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\Verb", cascade={"all"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="verb_id", referencedColumnName="identifier", unique=true)
     * })
     */
    public $verb;

    /**
     * @var StatementObject
     *
     * @ORM\OneToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\StatementObject", cascade={"all"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="object_id", referencedColumnName="identifier", unique=true, onDelete="CASCADE")
     * })
     */
    public $object;

    /**
     * @var Extensions
     *
     * @ORM\OneToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\Extensions", cascade={"all"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activityExtensions_id", referencedColumnName="identifier", unique=true, onDelete="CASCADE")
     * })
     */
    public $activityExtensions;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Entrili\XApiBundle\Repository\Mapping\Statement", mappedBy="object", cascade={"all"})
     */
    public $statements;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Entrili\XApiBundle\Repository\Mapping\StatementObject", mappedBy="group")
     */
    public $members;

    /**
     * @var StatementObject
     *
     * @ORM\ManyToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\StatementObject", inversedBy="members")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="group_id", referencedColumnName="identifier", onDelete="CASCADE")
     * })
     */
    public $group;

    /**
     * @var Context
     *
     * @ORM\ManyToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\Context", inversedBy="parentActivities", cascade={"remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parentContext_id", referencedColumnName="identifier", onDelete="CASCADE")
     * })
     */
    public $parentContext;

    /**
     * @var Context
     *
     * @ORM\ManyToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\Context", inversedBy="groupingActivities", cascade={"remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="groupingContext_id", referencedColumnName="identifier", onDelete="CASCADE")
     * })
     */
    public $groupingContext;

    /**
     * @var Context
     *
     * @ORM\ManyToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\Context", inversedBy="categoryActivities", cascade={"remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categoryContext_id", referencedColumnName="identifier", onDelete="CASCADE")
     * })
     */
    public $categoryContext;

    /**
     * @var Context
     *
     * @ORM\ManyToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\Context", inversedBy="otherActivities", cascade={"remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="otherContext_id", referencedColumnName="identifier", onDelete="CASCADE")
     * })
     */
    public $otherContext;

    /**
     * @ORM\Column(name="hash", type="string", nullable=true )
     */
    private $hash;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->statements = new ArrayCollection();
        $this->members = new ArrayCollection();
    }

    /**
     * @param ObjectModel $model
     * @return StatementObject
     */
    public static function fromModel(ObjectModel $model)
    {
        if ($model instanceof ActorModel) {
            return self::fromActor($model);
        }

        if ($model instanceof StatementReference) {
            $object = new self();
            $object->type = self::TYPE_STATEMENT_REFERENCE;
            $object->referencedStatementId = $model->getStatementId()->getValue();

            return $object;
        }

        if ($model instanceof SubStatement) {
            return self::fromSubStatement($model);
        }

        return self::fromActivity($model);
    }

    /**
     * @return Activity|Agent|Group|StatementReference|SubStatement
     */
    public function getModel()
    {
        if (self::TYPE_AGENT === $this->type || self::TYPE_GROUP === $this->type) {
            return $this->getActorModel();
        }

        if (self::TYPE_STATEMENT_REFERENCE === $this->type) {
            return new StatementReference(StatementId::fromString($this->referencedStatementId));
        }

        if (self::TYPE_SUB_STATEMENT === $this->type) {
            return $this->getSubStatementModel();
        }

        return $this->getActivityModel();
    }

    /**
     * @param Activity|StatementObject $model
     * @return StatementObject
     */
    private static function fromActivity(Activity $model)
    {
        $object = new self();
        $object->activityId = $model->getId()->getValue();

        if (null !== $definition = $model->getDefinition()) {
            $object->hasActivityDefinition = true;

            if (null !== $name = $definition->getName()) {
                $object->hasActivityName = true;
                $object->activityName = [];

                foreach ($name->languageTags() as $languageTag) {
                    $object->activityName[$languageTag] = $name[$languageTag];
                }
            } else {
                $object->hasActivityName = false;
            }

            if (null !== $description = $definition->getDescription()) {
                $object->hasActivityDescription = true;
                $object->activityDescription = [];

                foreach ($description->languageTags() as $languageTag) {
                    $object->activityDescription[$languageTag] = $description[$languageTag];
                }
            } else {
                $object->hasActivityDescription = false;
            }

            if (null !== $type = $definition->getType()) {
                $object->activityType = $type->getValue();
            }

            if (null !== $moreInfo = $definition->getMoreInfo()) {
                $object->activityMoreInfo = $moreInfo->getValue();
            }

            if (null !== $extensions = $definition->getExtensions()) {
                $object->activityExtensions = Extensions::fromModel($extensions);
            }
        } else {
            $object->hasActivityDefinition = false;
        }

        return $object;
    }

    /**
     * @param ActorModel $model
     * @return StatementObject
     */
    private static function fromActor(ActorModel $model)
    {
        $inverseFunctionalIdentifier = $model->getInverseFunctionalIdentifier();

        $object = new self();
        $object->mboxSha1Sum = $inverseFunctionalIdentifier->getMboxSha1Sum();
        $object->openId = $inverseFunctionalIdentifier->getOpenId();

        if (null !== $mbox = $inverseFunctionalIdentifier->getMbox()) {
            $object->mbox = $mbox->getValue();
        }

        if (null !== $account = $inverseFunctionalIdentifier->getAccount()) {
            $object->accountName = $account->getName();
            $object->accountHomePage = $account->getHomePage()->getValue();
        }

        if ($model instanceof Group) {
            $object->type = self::TYPE_GROUP;
            $object->members = [];

            foreach ($model->getMembers() as $agent) {
                $object->members[] = self::fromActor($agent);
            }
        } else {
            $object->type = self::TYPE_AGENT;
        }

        return $object;
    }

    /**
     * @param SubStatement $model
     * @return StatementObject
     */
    private static function fromSubStatement(SubStatement $model)
    {
        $object = new self();
        $object->type = self::TYPE_SUB_STATEMENT;
        $object->actor = StatementObject::fromModel($model->getActor());
        $object->verb = Verb::fromModel($model->getVerb());
        $object->object = StatementObject::fromModel($model->getObject());

        return $object;
    }

    /**
     * @return Activity
     */
    private function getActivityModel()
    {
        $definition = null;
        $type = null;
        $moreInfo = null;

        if ($this->hasActivityDefinition) {
            $name = null;
            $description = null;
            $extensions = null;

            if ($this->hasActivityName) {
                $name = LanguageMap::create($this->activityName);
            }

            if ($this->hasActivityDescription) {
                $description = LanguageMap::create($this->activityDescription);
            }

            if (null !== $this->activityType) {
                $type = IRI::fromString($this->activityType);
            }

            if (null !== $this->activityMoreInfo) {
                $moreInfo = IRL::fromString($this->activityMoreInfo);
            }

            if (null !== $this->activityExtensions) {
                $extensions = $this->activityExtensions->getModel();
            }

            $definition = new Definition($name, $description, $type, $moreInfo, $extensions);
        }

        return new Activity(IRI::fromString($this->activityId), $definition);
    }

    /**
     * @return Agent|Group
     */
    private function getActorModel()
    {
        $inverseFunctionalIdentifier = null;

        if (null !== $this->mbox) {
            $inverseFunctionalIdentifier = InverseFunctionalIdentifier::withMbox(IRI::fromString($this->mbox));
        } elseif (null !== $this->mboxSha1Sum) {
            $inverseFunctionalIdentifier = InverseFunctionalIdentifier::withMboxSha1Sum($this->mboxSha1Sum);
        } elseif (null !== $this->openId) {
            $inverseFunctionalIdentifier = InverseFunctionalIdentifier::withOpenId($this->openId);
        } elseif (null !== $this->accountName && null !== $this->accountHomePage) {
            $inverseFunctionalIdentifier = InverseFunctionalIdentifier::withAccount(new Account($this->accountName, IRL::fromString($this->accountHomePage)));
        }

        if (self::TYPE_GROUP === $this->type) {
            $members = [];

            foreach ($this->members as $agent) {
                $members[] = $agent->getModel();
            }

            return new Group($inverseFunctionalIdentifier, $this->name, $members);
        }

        return new Agent($inverseFunctionalIdentifier, $this->name);
    }

    /**
     * @return SubStatement
     */
    private function getSubStatementModel()
    {
        $result = null;
        $context = null;

        return new SubStatement(
            $this->actor->getModel(),
            $this->verb->getModel(),
            $this->object->getModel(),
            $result,
            $context
        );
    }

    /**
     * @ORM\PreFlush()
     */
    public function setHash()
    {
        $this->hash = md5(
            $this->activityId
            . json_encode($this->activityName)
            . $this->activityType
        );
    }
}
