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

use Entrili\XApiBundle\Model\Definition;
use Entrili\XApiBundle\Model\Extensions;
use Entrili\XApiBundle\Model\IRI;
use Entrili\XApiBundle\Model\IRL;
use Entrili\XApiBundle\Model\LanguageMap;

/**
 * An interaction where the learner is asked to order items in a set.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class SequencingInteractionDefinition extends InteractionDefinition
{
    private $choices;

    /**
     * @param LanguageMap|null $name
     * @param LanguageMap|null $description
     * @param IRI|null $type
     * @param IRL|null $moreInfo
     * @param Extensions|null $extensions
     * @param string[]|null $correctResponsesPattern
     * @param InteractionComponent[]|null $choices
     */
    public function __construct(LanguageMap $name = null, LanguageMap $description = null, IRI $type = null, IRL $moreInfo = null, Extensions $extensions = null, array $correctResponsesPattern = null, array $choices = null)
    {
        parent::__construct($name, $description, $type, $moreInfo, $extensions, $correctResponsesPattern);

        $this->choices = $choices;
    }

    /**
     * @param InteractionComponent[]|null $choices
     *
     * @return static
     */
    public function withChoices(array $choices = null)
    {
        $interaction = clone $this;
        $interaction->choices = $choices;

        return $interaction;
    }

    public function getChoices()
    {
        return $this->choices;
    }

    public function equals(Definition $definition)
    {
        if (!parent::equals($definition)) {
            return false;
        }

        if (!$definition instanceof SequencingInteractionDefinition) {
            return false;
        }

        if (null !== $this->choices xor null !== $definition->choices) {
            return false;
        }

        if (null !== $this->choices) {
            if (count($this->choices) !== count($definition->choices)) {
                return false;
            }

            foreach ($this->choices as $key => $choice) {
                if (!isset($definition->choices[$key])) {
                    return false;
                }

                if (!$choice->equals($definition->choices[$key])) {
                    return false;
                }
            }
        }

        return true;
    }
}
