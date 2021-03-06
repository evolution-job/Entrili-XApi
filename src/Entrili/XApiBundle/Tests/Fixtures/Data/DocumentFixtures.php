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

use Entrili\XApiBundle\Model\Activity;
use Entrili\XApiBundle\Model\ActivityProfile;
use Entrili\XApiBundle\Model\ActivityProfileDocument;
use Entrili\XApiBundle\Model\Agent;
use Entrili\XApiBundle\Model\AgentProfile;
use Entrili\XApiBundle\Model\AgentProfileDocument;
use Entrili\XApiBundle\Model\DocumentData;
use Entrili\XApiBundle\Model\InverseFunctionalIdentifier;
use Entrili\XApiBundle\Model\IRI;
use Entrili\XApiBundle\Model\State;
use Entrili\XApiBundle\Model\StateDocument;

/**
 * Document fixtures.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class DocumentFixtures
{
    /**
     * Loads empty document data.
     *
     * @return DocumentData
     */
    public static function getEmptyDocumentData()
    {
        return new DocumentData();
    }

    /**
     * Loads document data.
     *
     * @return DocumentData
     */
    public static function getDocumentData()
    {
        return new DocumentData(array('x' => 'foo', 'y' => 'bar'));
    }

    /**
     * Loads an activity profile document.
     *
     * @param DocumentData $documentData The document data, by default, a some
     *                                   default data will be used
     *
     * @return ActivityProfileDocument
     */
    public static function getActivityProfileDocument(DocumentData $documentData = null)
    {
        if (null === $documentData) {
            $documentData = static::getDocumentData();
        }

        $activityProfile = new ActivityProfile('profile-id', new Activity(IRI::fromString('activity-id')));

        return new ActivityProfileDocument($activityProfile, $documentData);
    }

    /**
     * Loads an agent profile document.
     *
     * @param DocumentData $documentData The document data, by default, a some
     *                                   default data will be used
     *
     * @return AgentProfileDocument
     */
    public static function getAgentProfileDocument(DocumentData $documentData = null)
    {
        if (null === $documentData) {
            $documentData = static::getDocumentData();
        }

        return new AgentProfileDocument(new AgentProfile('profile-id', new Agent(InverseFunctionalIdentifier::withMbox(IRI::fromString('mailto:christian@example.com')))), $documentData);
    }

    /**
     * Loads a state document.
     *
     * @param DocumentData $documentData The document data, by default, a some
     *                                   default data will be used
     *
     * @return StateDocument
     */
    public static function getStateDocument(DocumentData $documentData = null)
    {
        if (null === $documentData) {
            $documentData = static::getDocumentData();
        }

        $agent = new Agent(InverseFunctionalIdentifier::withMbox(IRI::fromString('mailto:alice@example.com')));
        $activity = new Activity(IRI::fromString('activity-id'));

        return new StateDocument(new State($activity, $agent, 'state-id'), $documentData);
    }
}
