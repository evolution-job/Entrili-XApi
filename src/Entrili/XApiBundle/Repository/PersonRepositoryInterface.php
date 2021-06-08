<?php

namespace Entrili\XApiBundle\Repository;

use Entrili\XApiBundle\Model\Agent;
use Entrili\XApiBundle\Model\Person;

/**
 * Public API of an Experience API (xAPI) {@link Person} repository.
 *
 * @author Jérôme Parmentier <jerome.parmentier@acensi.fr>
 */
interface PersonRepositoryInterface
{
    /**
     * Finds a {@link Person person} related to one {@link Agent agent}.
     *
     * @param Agent $agent The agent to filter by
     *
     * @return Person The related person
     */
    public function findRelatedPersonTo(Agent $agent);
}
