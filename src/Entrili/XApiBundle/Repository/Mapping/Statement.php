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
use Entrili\XApiBundle\Model\Statement as StatementModel;
use Entrili\XApiBundle\Model\StatementId;

/**
 * Statement
 *
 * @ORM\Table(name="xapi_statement")
 * @ORM\Entity(repositoryClass="Entrili\XApiBundle\Repository\StatementRepository")
 */
class Statement
{
    /**
     * @var integer
     *
     * @ORM\Column(name="created", type="bigint", nullable=true)
     */
    public $created;

    /**
     * @var integer
     *
     * @ORM\Column(name="`stored`", type="bigint", nullable=true)
     */
    public $stored;

    /**
     * @var bool
     *
     * @ORM\Column(name="hasAttachments", type="boolean")
     */
    public $hasAttachments;

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    public $id;

    /**
     * @var \Entrili\XApiBundle\Repository\Mapping\StatementObject
     *
     * @ORM\OneToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\StatementObject", cascade={"all"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actor_id", referencedColumnName="identifier", unique=true)
     * })
     */
    public $actor;

    /**
     * @var \Entrili\XApiBundle\Repository\Mapping\Result
     *
     * @ORM\OneToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\Result", cascade={"all"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="result_id", referencedColumnName="identifier", unique=true)
     * })
     */
    public $result;

    /**
     * @var \Entrili\XApiBundle\Repository\Mapping\StatementObject
     *
     * @ORM\OneToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\StatementObject", cascade={"all"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="authority_id", referencedColumnName="identifier", unique=true)
     * })
     */
    public $authority;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entrili\XApiBundle\Repository\Mapping\Attachment", mappedBy="statement", cascade={"all"})
     */
    public $attachments;

    /**
     * @var \Entrili\XApiBundle\Repository\Mapping\Context
     *
     * @ORM\ManyToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\Context", inversedBy="statements", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="context_id", referencedColumnName="identifier", onDelete="CASCADE")
     * })
     */
    public $context;

    /**
     * @var \Entrili\XApiBundle\Repository\Mapping\StatementObject
     *
     * @ORM\ManyToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\StatementObject", inversedBy="statements", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="object_id", referencedColumnName="identifier")
     * })
     */
    public $object;

    /**
     * @var \Entrili\XApiBundle\Repository\Mapping\Verb
     *
     * @ORM\ManyToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\Verb", inversedBy="statements", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="verb_id", referencedColumnName="identifier")
     * })
     */
    public $verb;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    /**
     * @param StatementModel $model
     * @return Statement
     */
    public static function fromModel(StatementModel $model)
    {
        $statement = new self();
        $statement->id = $model->getId()->getValue();
        $statement->actor = StatementObject::fromModel($model->getActor());
        $statement->verb = Verb::fromModel($model->getVerb());
        $statement->object = StatementObject::fromModel($model->getObject());

        if (null !== $model->getCreated()) {
            $statement->created = $model->getCreated()->getTimestamp();
        }

        if (null !== $result = $model->getResult()) {
            $statement->result = Result::fromModel($result);
        }

        if (null !== $authority = $model->getAuthority()) {
            $statement->authority = StatementObject::fromModel($authority);
        }

        if (null !== $context = $model->getContext()) {
            $statement->context = Context::fromModel($context);
        }

        if (null !== $attachments = $model->getAttachments()) {
            $statement->hasAttachments = true;
            $statement->attachments = array();

            foreach ($attachments as $attachment) {
                $mappedAttachment = Attachment::fromModel($attachment);
                $mappedAttachment->statement = $statement;
                $statement->attachments[] = $mappedAttachment;
            }
        } else {
            $statement->hasAttachments = false;
        }

        return $statement;
    }

    /**
     * @return StatementModel
     */
    public function getModel()
    {
        $result = null;
        $authority = null;
        $created = null;
        $stored = null;
        $context = null;
        $attachments = null;

        if (null !== $this->result) {
            $result = $this->result->getModel();
        }

        if (null !== $this->authority) {
            $authority = $this->authority->getModel();
        }

        if (null !== $this->created) {
            $created = new \DateTime('@' . $this->created);
        }

        if (null !== $this->stored) {
            $stored = new \DateTime('@' . $this->stored);
        }

        if (null !== $this->context) {
            $context = $this->context->getModel();
        }

        if ($this->hasAttachments) {
            $attachments = array();

            foreach ($this->attachments as $attachment) {
                $attachments[] = $attachment->getModel();
            }
        }

        return new StatementModel(
            StatementId::fromString($this->id),
            $this->actor->getModel(),
            $this->verb->getModel(),
            $this->object->getModel(),
            $result,
            $authority,
            $created,
            $stored,
            $context,
            $attachments
        );
    }
}
