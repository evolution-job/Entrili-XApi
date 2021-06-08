<?php

namespace Entrili\XApiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Entrili\XApiBundle\Model\State;
use Entrili\XApiBundle\Repository\Mapping\State as MappedState;
use Exception;

/**
 * Class StateRepository
 *
 * @package Entrili\XApiBundle\Repository
 */
final class StateRepository extends EntityRepository implements StateRepositoryInterface
{
    /**
     * @param array $criteria
     * @return MappedState|null|object
     */
    public function findState(array $criteria)
    {
        return parent::findOneBy($criteria);
    }

    /**
     * @return bool
     */
    private function flush()
    {
        try {
            $this->_em->flush();

            return true;
        } catch (Exception $exception) {
            // ...
        }

        return false;
    }

    /**
     * @param State $state
     * @param bool $flush
     */
    public function storeState(State $state, $flush = true)
    {
        /**
         * Mapped State
         */
        $mappedState = MappedState::fromModel($state);
        $this->_em->persist($mappedState);

        /**
         * Flush
         */
        if ($flush) {

            $this->flush();
        }
    }

    /**
     * @param MappedState $state
     * @param bool $flush
     */
    public function updateState(MappedState $state, $flush = true)
    {
        $this->_em->persist($state);
        /**
         * Flush
         */
        if ($flush) {

            $this->flush();
        }
    }
}