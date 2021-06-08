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
use Entrili\XApiBundle\Model\IRI;
use Entrili\XApiBundle\Model\LanguageMap;
use Entrili\XApiBundle\Model\Verb as VerbModel;

/**
 * Verb
 *
 * @ORM\Entity
 * @ORM\Table("xapi_verb", indexes={
 *     @ORM\Index(name="identifier_index", columns={"identifier"})
 * })
 */
class Verb
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", unique=true)
     */
    public $id;

    /**
     * @var array
     *
     * @ORM\Column(name="display", type="json")
     */
    public $display;

    /**
     * @var integer
     *
     * @ORM\Column(name="identifier", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $identifier;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Entrili\XApiBundle\Repository\Mapping\Statement", mappedBy="verb", cascade={"detach"})
     */
    public $statements;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->statements = new ArrayCollection();
    }

    public function getModel()
    {
        $display = null;

        if (null !== $this->display) {
            $display = LanguageMap::create($this->display);
        }

        return new VerbModel(IRI::fromString($this->id), $display);
    }

    public static function fromModel(VerbModel $model)
    {
        $verb = new self();
        $verb->id = $model->getId()->getValue();

        if (null !== $display = $model->getDisplay()) {
            $verb->display = array();

            foreach ($display->languageTags() as $languageTag) {
                $verb->display[$languageTag] = $display[$languageTag];
            }
        }

        $verb->statements = $model->getStatements();

        return $verb;
    }
}
