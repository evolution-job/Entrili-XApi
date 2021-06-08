<?php

namespace Entrili\XApiBundle\Repository;

use Entrili\XApiBundle\Exception\NotFoundException;
use Entrili\XApiBundle\Model\Activity;
use Entrili\XApiBundle\Model\IRI;

/**
 * Public API of an Experience API (xAPI) {@link Activity} repository.
 *
 * @author Jérôme Parmentier <jerome.parmentier@acensi.fr>
 */
interface ActivityRepositoryInterface
{
    /**
     * Finds an {@link Activity} by id.
     *
     * @param IRI $activityId The activity id to filter by
     *
     * @return Activity The activity
     *
     * @throws NotFoundException if no Activity with the given IRI does exist
     */
    public function findActivityById(IRI $activityId);
}