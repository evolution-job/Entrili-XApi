<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Model\Interaction;

use Entrili\XApiBundle\Model\LanguageMap;

/**
 * An XAPI activity interaction component.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class InteractionComponent
{
    private $id;
    private $description;

    /**
     * @param string $id
     * @param LanguageMap|null $description
     */
    public function __construct($id, LanguageMap $description = null)
    {
        $this->id = $id;
        $this->description = $description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function equals(InteractionComponent $interactionComponent)
    {
        if ($this->id !== $interactionComponent->id) {
            return false;
        }

        if (null !== $this->description xor null !== $interactionComponent->description) {
            return false;
        }

        if (null !== $this->description && null !== $interactionComponent->description && !$this->description->equals($interactionComponent->description)) {
            return false;
        }

        return true;
    }
}
