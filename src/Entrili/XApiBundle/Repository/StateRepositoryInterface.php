<?php

namespace Entrili\XApiBundle\Repository;

use Entrili\XApiBundle\Model\State;

/**
 * Public API of an Experience API (xAPI) {@link State} repository.
 */
interface StateRepositoryInterface
{
    /**
     * @param array $criteria
     *
     * @return State The statement or null if no matching statement
     *                   has been found
     */
    public function findState(array $criteria);

    /**
     * Writes a {@link Statement} to the underlying data storage.
     *
     * @param State $statement The statement to store
     * @param bool $flush Whether or not to flush the managed objects
     *                             immediately (i.e. write them to the data
     *                             storage)
     *
     * @return State The id of the created Statement
     */
    public function storeState(State $state, $flush = true);
}
