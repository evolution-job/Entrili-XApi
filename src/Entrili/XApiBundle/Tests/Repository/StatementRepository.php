<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Tests\Repository;

use Doctrine\Common\Persistence\ObjectManager;
use Entrili\XApiBundle\Model\Actor;
use Entrili\XApiBundle\Model\Statement;
use Entrili\XApiBundle\Model\StatementId;
use Entrili\XApiBundle\Model\StatementsFilter;
use Entrili\XApiBundle\Repository\Mapping\Statement as MappedStatement;
use Entrili\XApiBundle\Repository\StatementRepositoryInterface;

/**
 * Statement repository clearing the object manager between read and write operations.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class StatementRepository implements StatementRepositoryInterface
{
    private $repository;
    private $objectManager;

    public function __construct(StatementRepositoryInterface $repository, ObjectManager $objectManager)
    {
        $this->repository = $repository;
        $this->objectManager = $objectManager;
    }

    /**
     * @param array $criteria
     * @return MappedStatement|null|object
     */
    public function findStatement(array $criteria)
    {
        $statement = $this->repository->findStatement($criteria);
        $this->objectManager->clear();

        return $statement;
    }

    /**
     * @param array $criteria
     * @return array|MappedStatement[]
     */
    public function findStatements(array $criteria)
    {
        $statements = $this->repository->findStatements($criteria);
        $this->objectManager->clear();

        return $statements;
    }

    /**
     * {@inheritdoc}
     */
    public function findStatementById(StatementId $statementId, Actor $authority = null)
    {
        $statement = $this->repository->findStatementById($statementId, $authority);
        $this->objectManager->clear();

        return $statement;
    }

    /**
     * {@inheritdoc}
     */
    public function findVoidedStatementById(StatementId $voidedStatementId, Actor $authority = null)
    {
        $statement = $this->repository->findVoidedStatementById($voidedStatementId, $authority);
        $this->objectManager->clear();

        return $statement;
    }

    /**
     * {@inheritdoc}
     */
    public function findStatementsBy(StatementsFilter $criteria, Actor $authority = null)
    {
        $statements = $this->repository->findStatementsBy($criteria, $authority);
        $this->objectManager->clear();

        return $statements;
    }

    /**
     * {@inheritdoc}
     */
    public function storeStatement(Statement $statement, $flush = true)
    {
        $statementId = $this->repository->storeStatement($statement);
        $this->objectManager->clear();

        return $statementId;
    }
}
