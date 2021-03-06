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
 * Base class for interaction definitions of an {@link Activity}.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class InteractionDefinition extends Definition
{
    private $correctResponsesPattern;

    /**
     * @param LanguageMap|null $name
     * @param LanguageMap|null $description
     * @param IRI|null $type
     * @param IRL|null $moreInfo
     * @param Extensions|null $extensions
     * @param string[]|null $correctResponsesPattern
     */
    public function __construct(LanguageMap $name = null, LanguageMap $description = null, IRI $type = null, IRL $moreInfo = null, Extensions $extensions = null, array $correctResponsesPattern = null)
    {
        parent::__construct($name, $description, $type, $moreInfo, $extensions);

        $this->correctResponsesPattern = $correctResponsesPattern;
    }

    /**
     * @param string[]|null $correctResponsesPattern
     *
     * @return static
     */
    public function withCorrectResponsesPattern(array $correctResponsesPattern = null)
    {
        $interaction = clone $this;
        $interaction->correctResponsesPattern = $correctResponsesPattern;

        return $interaction;
    }

    public function getCorrectResponsesPattern()
    {
        return $this->correctResponsesPattern;
    }

    public function equals(Definition $definition)
    {
        if (!parent::equals($definition)) {
            return false;
        }

        if (!$definition instanceof InteractionDefinition) {
            return false;
        }

        if (null !== $this->correctResponsesPattern xor null !== $definition->correctResponsesPattern) {
            return false;
        }

        if (null !== $this->correctResponsesPattern) {
            if (count($this->correctResponsesPattern) !== count($definition->correctResponsesPattern)) {
                return false;
            }

            foreach ($this->correctResponsesPattern as $value) {
                if (!in_array($value, $definition->correctResponsesPattern, true)) {
                    return false;
                }
            }
        }

        return true;
    }
}
