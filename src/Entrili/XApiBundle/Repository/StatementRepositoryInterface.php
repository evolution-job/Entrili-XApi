<?php

namespace Entrili\XApiBundle\Repository;

use Entrili\XApiBundle\Exception\NotFoundException;
use Entrili\XApiBundle\Model\Actor;
use Entrili\XApiBundle\Model\Statement;
use Entrili\XApiBundle\Model\StatementId;
use Entrili\XApiBundle\Model\StatementsFilter;

/**
 * Public API of an Experience API (xAPI) {@link Statement} repository.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface StatementRepositoryInterface
{
    /**
     * @param array $criteria
     *
     * @return Statement The statement or null if no matching statement
     *                   has been found
     */
    public function findStatement(array $criteria);

    /**
     * @param array $criteria
     *
     * @return Statement[] The statements matching the given criteria
     */
    public function findStatements(array $criteria);

    /**
     * Finds a {@link Statement} by id.
     *
     * @param StatementId $statementId The statement id to filter by
     * @param Actor|null $authority (Optional) actor that must be the authority
     *                                 of the returned statement
     *
     * @return Statement The statement
     *
     * @throws NotFoundException if no Statement with the given UUID does exist
     */
    public function findStatementById(StatementId $statementId, Actor $authority = null);

    /**
     * Finds a voided {@link Statement} by id.
     *
     * @param StatementId $voidedStatementId The voided statement id to filter
     *                                       by
     * @param Actor|null $authority (Optional) actor that must be the
     *                                       authority of the returned statement
     *
     * @return Statement The statement
     *
     * @throws NotFoundException if no voided Statement with the given UUID
     *                           does exist
     */
    public function findVoidedStatementById(StatementId $voidedStatementId, Actor $authority = null);

    /**
     * Finds a collection of {@link Statement Statements} filtered by the given
     * criteria.
     *
     * @param StatementsFilter $criteria The criteria to filter by
     * @param Actor|null $authority (Optional) actor that must be the
     *                                    authority of the returned statements
     *
     * @return Statement[] The statements
     */
    public function findStatementsBy(StatementsFilter $criteria, Actor $authority = null);

    /**
     * Writes a {@link Statement} to the underlying data storage.
     *
     * @param Statement $statement The statement to store
     * @param bool $flush Whether or not to flush the managed objects
     *                             immediately (i.e. write them to the data
     *                             storage)
     *
     * @return StatementId The id of the created Statement
     */
    public function storeStatement(Statement $statement, $flush = true);
}
