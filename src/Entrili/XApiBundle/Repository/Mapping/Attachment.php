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
use Entrili\XApiBundle\Model\Attachment as AttachmentModel;
use Entrili\XApiBundle\Model\IRI;
use Entrili\XApiBundle\Model\IRL;
use Entrili\XApiBundle\Model\LanguageMap;

/**
 * Attachment
 *
 * @ORM\Table(name="xapi_attachment")
 * @ORM\Entity
 */
class Attachment
{
    /**
     * @var string
     *
     * @ORM\Column(name="usageType", type="string")
     */
    public $usageType;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    public $content;

    /**
     * @var string
     *
     * @ORM\Column(name="contentType", type="string")
     */
    public $contentType;

    /**
     * @var integer
     *
     * @ORM\Column(name="length", type="integer")
     */
    public $length;

    /**
     * @var string
     *
     * @ORM\Column(name="sha2", type="string")
     */
    public $sha2;

    /**
     * @var array
     *
     * @ORM\Column(name="display", type="json")
     */
    public $display;

    /**
     * @var bool
     *
     * @ORM\Column(name="hasDescription", type="boolean")
     */
    public $hasDescription;

    /**
     * @var array
     *
     * @ORM\Column(name="description", type="json", nullable=true)
     */
    public $description;

    /**
     * @var string
     *
     * @ORM\Column(name="fileUrl", type="string", nullable=true)
     */
    public $fileUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="identifier", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $identifier;

    /**
     * @var Statement
     *
     * @ORM\ManyToOne(targetEntity="Entrili\XApiBundle\Repository\Mapping\Statement", inversedBy="attachments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="statement_id", referencedColumnName="id")
     * })
     */
    public $statement;

    /**
     * @param AttachmentModel $model
     * @return Attachment
     */
    public static function fromModel(AttachmentModel $model)
    {
        $attachment = new self();
        $attachment->usageType = $model->getUsageType()->getValue();
        $attachment->contentType = $model->getContentType();
        $attachment->length = $model->getLength();
        $attachment->sha2 = $model->getSha2();
        $attachment->display = array();
        if (null !== $model->getFileUrl()) {
            $attachment->fileUrl = $model->getFileUrl()->getValue();
        }
        $attachment->content = $model->getContent();
        $display = $model->getDisplay();

        foreach ($display->languageTags() as $languageTag) {
            $attachment->display[$languageTag] = $display[$languageTag];
        }

        if (null !== $description = $model->getDescription()) {
            $attachment->hasDescription = true;
            $attachment->description = array();
            foreach ($description->languageTags() as $languageTag) {
                $attachment->description[$languageTag] = $description[$languageTag];
            }
        } else {
            $attachment->hasDescription = false;
        }

        return $attachment;
    }

    /**
     * @return AttachmentModel
     */
    public function getModel()
    {
        $description = null;
        $fileUrl = null;

        if ($this->hasDescription) {
            $description = LanguageMap::create($this->description);
        }

        if (null !== $this->fileUrl) {
            $fileUrl = IRL::fromString($this->fileUrl);
        }

        return new AttachmentModel(IRI::fromString($this->usageType), $this->contentType, $this->length, $this->sha2, LanguageMap::create($this->display), $description, $fileUrl, $this->content);
    }
}
