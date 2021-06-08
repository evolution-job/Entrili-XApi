<?php

namespace Entrili\XApiBundle\Serializer\Normalizer;

use Entrili\XApiBundle\Model\Definition;
use Entrili\XApiBundle\Model\Interaction\ChoiceInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\FillInInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\InteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\LikertInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\LongFillInInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\MatchingInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\NumericInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\OtherInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\PerformanceInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\SequencingInteractionDefinition;
use Entrili\XApiBundle\Model\Interaction\TrueFalseInteractionDefinition;
use Entrili\XApiBundle\Model\IRI;
use Entrili\XApiBundle\Model\IRL;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

/**
 * Normalizes and denormalizes PHP arrays to {@link Definition} instances.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class DefinitionNormalizer extends Normalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        if (!$object instanceof Definition) {
            return;
        }

        $data = array();

        if (null !== $name = $object->getName()) {
            $data['name'] = $this->normalizeAttribute($name, $format, $context);
        }

        if (null !== $description = $object->getDescription()) {
            $data['description'] = $this->normalizeAttribute($description, $format, $context);
        }

        if (null !== $type = $object->getType()) {
            $data['type'] = $type->getValue();
        }

        if (null !== $moreInfo = $object->getMoreInfo()) {
            $data['moreInfo'] = $moreInfo->getValue();
        }

        if (null !== $extensions = $object->getExtensions()) {
            $data['extensions'] = $this->normalizeAttribute($extensions, $format, $context);
        }

        if ($object instanceof InteractionDefinition) {
            if (null !== $correctResponsesPattern = $object->getCorrectResponsesPattern()) {
                $data['correctResponsesPattern'] = $object->getCorrectResponsesPattern();
            }

            switch (true) {
                case $object instanceof ChoiceInteractionDefinition:
                    $data['interactionType'] = 'choice';

                    if (null !== $choices = $object->getChoices()) {
                        $data['choices'] = $this->normalizeAttribute($choices, $format, $context);
                    }
                    break;
                case $object instanceof FillInInteractionDefinition:
                    $data['interactionType'] = 'fill-in';
                    break;
                case $object instanceof LikertInteractionDefinition:
                    $data['interactionType'] = 'likert';

                    if (null !== $scale = $object->getScale()) {
                        $data['scale'] = $this->normalizeAttribute($scale, $format, $context);
                    }
                    break;
                case $object instanceof LongFillInInteractionDefinition:
                    $data['interactionType'] = 'long-fill-in';
                    break;
                case $object instanceof MatchingInteractionDefinition:
                    $data['interactionType'] = 'matching';

                    if (null !== $source = $object->getSource()) {
                        $data['source'] = $this->normalizeAttribute($source, $format, $context);
                    }

                    if (null !== $target = $object->getTarget()) {
                        $data['target'] = $this->normalizeAttribute($target, $format, $context);
                    }
                    break;
                case $object instanceof NumericInteractionDefinition:
                    $data['interactionType'] = 'numeric';
                    break;
                case $object instanceof OtherInteractionDefinition:
                    $data['interactionType'] = 'other';
                    break;
                case $object instanceof PerformanceInteractionDefinition:
                    $data['interactionType'] = 'performance';

                    if (null !== $steps = $object->getSteps()) {
                        $data['steps'] = $this->normalizeAttribute($steps, $format, $context);
                    }
                    break;
                case $object instanceof SequencingInteractionDefinition:
                    $data['interactionType'] = 'sequencing';

                    if (null !== $choices = $object->getChoices()) {
                        $data['choices'] = $this->normalizeAttribute($choices, $format, $context);
                    }
                    break;
                case $object instanceof TrueFalseInteractionDefinition:
                    $data['interactionType'] = 'true-false';
                    break;
            }
        }

        if (empty($data)) {
            return new \stdClass();
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Definition;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = null, array $context = array())
    {
        if (isset($data['interactionType'])) {
            switch ($data['interactionType']) {
                case 'choice':
                    $definition = new ChoiceInteractionDefinition();

                    if (isset($data['choices'])) {
                        $definition = $definition->withChoices($this->denormalizeData($data['choices'], 'Entrili\XApiBundle\Model\Interaction\InteractionComponent[]', $format, $context));
                    }
                    break;
                case 'fill-in':
                    $definition = new FillInInteractionDefinition();
                    break;
                case 'likert':
                    $definition = new LikertInteractionDefinition();

                    if (isset($data['scale'])) {
                        $definition = $definition->withScale($this->denormalizeData($data['scale'], 'Entrili\XApiBundle\Model\Interaction\InteractionComponent[]', $format, $context));
                    }
                    break;
                case 'long-fill-in':
                    $definition = new LongFillInInteractionDefinition();
                    break;
                case 'matching':
                    $definition = new MatchingInteractionDefinition();

                    if (isset($data['source'])) {
                        $definition = $definition->withSource($this->denormalizeData($data['source'], 'Entrili\XApiBundle\Model\Interaction\InteractionComponent[]', $format, $context));
                    }

                    if (isset($data['target'])) {
                        $definition = $definition->withTarget($this->denormalizeData($data['target'], 'Entrili\XApiBundle\Model\Interaction\InteractionComponent[]', $format, $context));
                    }
                    break;
                case 'numeric':
                    $definition = new NumericInteractionDefinition();
                    break;
                case 'other':
                    $definition = new OtherInteractionDefinition();
                    break;
                case 'performance':
                    $definition = new PerformanceInteractionDefinition();

                    if (isset($data['steps'])) {
                        $definition = $definition->withSteps($this->denormalizeData($data['steps'], 'Entrili\XApiBundle\Model\Interaction\InteractionComponent[]', $format, $context));
                    }
                    break;
                case 'sequencing':
                    $definition = new SequencingInteractionDefinition();

                    if (isset($data['choices'])) {
                        $definition = $definition->withChoices($this->denormalizeData($data['choices'], 'Entrili\XApiBundle\Model\Interaction\InteractionComponent[]', $format, $context));
                    }
                    break;
                case 'true-false':
                    $definition = new TrueFalseInteractionDefinition();
                    break;
                default:
                    throw new InvalidArgumentException(sprintf('The interaction type "%s" is not supported.', $data['interactionType']));
            }

            if (isset($data['correctResponsesPattern'])) {
                $definition = $definition->withCorrectResponsesPattern($data['correctResponsesPattern']);
            }
        } else {
            $definition = new Definition();
        }

        if (isset($data['name'])) {
            $name = $this->denormalizeData($data['name'], 'Entrili\XApiBundle\Model\LanguageMap', $format, $context);
            $definition = $definition->withName($name);
        }

        if (isset($data['description'])) {
            $description = $this->denormalizeData($data['description'], 'Entrili\XApiBundle\Model\LanguageMap', $format, $context);
            $definition = $definition->withDescription($description);
        }

        if (isset($data['type'])) {
            $definition = $definition->withType(IRI::fromString($data['type']));
        }

        if (isset($data['moreInfo'])) {
            $definition = $definition->withMoreInfo(IRL::fromString($data['moreInfo']));
        }

        if (isset($data['extensions'])) {
            $extensions = $this->denormalizeData($data['extensions'], 'Entrili\XApiBundle\Model\Extensions', $format, $context);
            $definition = $definition->withExtensions($extensions);
        }

        return $definition;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        $supportedDefinitionClasses = array(
            'Entrili\XApiBundle\Model\Definition',
            'Entrili\XApiBundle\Model\Interaction\ChoiceInteractionDefinition',
            'Entrili\XApiBundle\Model\Interaction\FillInInteractionDefinition',
            'Entrili\XApiBundle\Model\Interaction\LikertInteractionDefinition',
            'Entrili\XApiBundle\Model\Interaction\LongFillInInteractionDefinition',
            'Entrili\XApiBundle\Model\Interaction\MatchingInteractionDefinition',
            'Entrili\XApiBundle\Model\Interaction\NumericInteractionDefinition',
            'Entrili\XApiBundle\Model\Interaction\OtherInteractionDefinition',
            'Entrili\XApiBundle\Model\Interaction\PerformanceInteractionDefinition',
            'Entrili\XApiBundle\Model\Interaction\SequencingInteractionDefinition',
            'Entrili\XApiBundle\Model\Interaction\TrueFalseInteractionDefinition',
        );

        return in_array($type, $supportedDefinitionClasses, true);
    }
}
