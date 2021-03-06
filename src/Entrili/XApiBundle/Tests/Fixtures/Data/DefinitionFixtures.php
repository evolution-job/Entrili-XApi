<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Tests\Fixtures\Data;

use Entrili\XApiBundle\Model\Definition;
use Entrili\XApiBundle\Model\Interaction\ChoiceInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\FillInInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\LikertInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\MatchingInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\NumericInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\OtherInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\PerformanceInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\SequencingInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\TrueFalseInteractionDefinition;
use Entrili\XApiBundle\Model\IRI;
use Entrili\XApiBundle\Model\IRL;
use Entrili\XApiBundle\Model\LanguageMap;

/**
 * xAPI activity definition fixtures.
 *
 * These fixtures are borrowed from the
 * {@link https://github.com/adlnet/xapi_Test Experience API Learning Record Store Conformance Test} package.
 */
class DefinitionFixtures
{
    public static function getEmptyDefinition()
    {
        return new Definition();
    }

    public static function getTypicalDefinition()
    {
        return new Definition();
    }

    public static function getNameDefinition()
    {
        return new Definition(LanguageMap::create(array('en-US' => 'test')));
    }

    public static function getDescriptionDefinition()
    {
        return new Definition(null, LanguageMap::create(array('en-US' => 'test')));
    }

    public static function getTypeDefinition()
    {
        return new Definition(null, null, IRI::fromString('http://id.tincanapi.com/activitytype/unit-test'));
    }

    public static function getMoreInfoDefinition()
    {
        return new Definition(null, null, null, IRL::fromString('https://github.com/adlnet/xapi_Test'));
    }

    public static function getExtensionsDefinition()
    {
        $definition = new Definition();
        $definition = $definition->withExtensions(ExtensionsFixtures::getMultiplePairsExtensions());

        return $definition;
    }

    public static function getEmptyExtensionsDefinition()
    {
        $definition = new Definition();
        $definition = $definition->withExtensions(ExtensionsFixtures::getEmptyExtensions());

        return $definition;
    }

    public static function getAllPropertiesDefinition()
    {
        return new Definition(
            LanguageMap::create(array('en-US' => 'test')),
            LanguageMap::create(array('en-US' => 'test')),
            IRI::fromString('http://id.tincanapi.com/activitytype/unit-test'),
            IRL::fromString('https://github.com/adlnet/xapi_Test'),
            ExtensionsFixtures::getTypicalExtensions()
        );
    }

    public static function getTrueFalseDefinition()
    {
        return new TrueFalseInteractionDefinition();
    }

    public static function getFillInDefinition()
    {
        return new FillInInteractionDefinition();
    }

    public static function getNumericDefinition()
    {
        return new NumericInteractionDefinition();
    }

    public static function getOtherDefinition()
    {
        return new OtherInteractionDefinition();
    }

    public static function getOtherWithCorrectResponsesPatternDefinition()
    {
        $otherDefinition = new OtherInteractionDefinition();
        $otherDefinition = $otherDefinition->withCorrectResponsesPattern(array('test'));

        return $otherDefinition;
    }

    public static function getChoiceDefinition()
    {
        $choiceDefinition = new ChoiceInteractionDefinition();
        $choiceDefinition = $choiceDefinition->withChoices(array(InteractionComponentFixtures::getTypicalInteractionComponent()));

        return $choiceDefinition;
    }

    public static function getSequencingDefinition()
    {
        $sequencingDefinition = new SequencingInteractionDefinition();
        $sequencingDefinition = $sequencingDefinition->withChoices(array(InteractionComponentFixtures::getTypicalInteractionComponent()));

        return $sequencingDefinition;
    }

    public static function getLikertDefinition()
    {
        $likertDefinition = new LikertInteractionDefinition();
        $likertDefinition = $likertDefinition->withScale(array(InteractionComponentFixtures::getTypicalInteractionComponent()));

        return $likertDefinition;
    }

    public static function getMatchingDefinition()
    {
        $matchingDefinition = new MatchingInteractionDefinition();
        $matchingDefinition = $matchingDefinition->withSource(array(InteractionComponentFixtures::getTypicalInteractionComponent()));
        $matchingDefinition = $matchingDefinition->withTarget(array(InteractionComponentFixtures::getTypicalInteractionComponent()));

        return $matchingDefinition;
    }

    public static function getPerformanceDefinition()
    {
        $performanceDefinition = new PerformanceInteractionDefinition();
        $performanceDefinition = $performanceDefinition->withSteps(array(InteractionComponentFixtures::getTypicalInteractionComponent()));

        return $performanceDefinition;
    }

    public static function getForQueryDefinition()
    {
        return new Definition(LanguageMap::create(array('en-US' => 'for query')));
    }
}
