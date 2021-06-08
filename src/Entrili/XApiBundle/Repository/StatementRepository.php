<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Entrili\XApiBundle\Exception\NotFoundException;
use Entrili\XApiBundle\Model\Actor;
use Entrili\XApiBundle\Model\Statement;
use Entrili\XApiBundle\Model\StatementId;
use Entrili\XApiBundle\Model\StatementsFilter;
use Entrili\XApiBundle\Model\Uuid as ModelUuid;
use Entrili\XApiBundle\Repository\Mapping\Result;
use Entrili\XApiBundle\Repository\Mapping\State;
use Entrili\XApiBundle\Repository\Mapping\Statement as MappedStatement;
use Entrili\XApiBundle\Repository\Mapping\StatementObject;
use Exception;
use Ramsey\Uuid\Uuid as RamseyUuid;


/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class StatementRepository extends EntityRepository implements StatementRepositoryInterface
{
    /**
     * @param array $criteria
     * @return MappedStatement|null|object
     */
    public function findStatement(array $criteria)
    {
        return parent::findOneBy($criteria);
    }

    /**
     * @param array $criteria
     * @return array|MappedStatement[]
     */
    public function findStatements(array $criteria)
    {
        return parent::findBy($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function findStatementById(StatementId $statementId, Actor $authority = null)
    {
        $criteria = ['id' => $statementId->getValue()];

        if (null !== $authority) {
            $criteria['authority'] = $authority;
        }

        $mappedStatement = $this->findStatement($criteria);

        if (null === $mappedStatement) {
            throw new NotFoundException('No statements could be found matching the given criteria.');
        }

        $statement = $mappedStatement->getModel();

        if ($statement->isVoidStatement()) {
            throw new NotFoundException('The stored statement is a voiding statement.');
        }

        return $statement;
    }

    /**
     * {@inheritdoc}
     */
    public function findVoidedStatementById(StatementId $voidedStatementId, Actor $authority = null)
    {
        $criteria = ['id' => $voidedStatementId->getValue()];

        if (null !== $authority) {
            $criteria['authority'] = $authority;
        }

        $mappedStatement = $this->findStatement($criteria);

        if (null === $mappedStatement) {
            throw new NotFoundException('No voided statements could be found matching the given criteria.');
        }

        $statement = $mappedStatement->getModel();

        if (!$statement->isVoidStatement()) {
            throw new NotFoundException('The stored statement is no voiding statement.');
        }

        return $statement;
    }

    /**
     * {@inheritdoc}
     */
    public function findStatementsBy(StatementsFilter $criteria, Actor $authority = null)
    {
        $criteria = $criteria->getFilter();

        if (null !== $authority) {
            $criteria['authority'] = $authority;
        }

        $mappedStatements = $this->findStatements($criteria);
        $statements = [];

        foreach ($mappedStatements as $mappedStatement) {
            $statements[] = $mappedStatement->getModel();
        }

        return $statements;
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
     * @param int $moduleReadId
     * @return array
     */
    public function getStatements($moduleReadId)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb
            ->select('st, ct, ac, ve, ob, re')
            ->from($this->_entityName, 'st')
            ->innerJoin('st.context', 'ct')
            ->leftJoin('st.actor', 'ac')
            ->leftJoin('st.verb', 've')
            ->leftJoin('st.object', 'ob')
            ->leftJoin('st.result', 're')
            ->where($qb->expr()->eq('ct.registration', ':id'))
            ->setParameter('id', $moduleReadId)
            ->orderBy('st.created', 'ASC');

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param $ids
     * @return mixed
     */
    public function removeResults($ids)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb
            ->delete(Result::class, 'res')
            ->where($qb->expr()->in('res.identifier', ':ids'))
            ->setParameter('ids', $ids);

        return $qb->getQuery()->execute();
    }

    /**
     * Remove XAPI orphan objects
     *
     * Warning: very slow request !
     */
    public function removeStatementObjects()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb
            ->select('partial obj.{identifier}')
            ->from('Entrili\XApiBundle\Repository\Mapping\StatementObject', 'obj')
            ->leftJoin($this->_entityName, 'st1', 'WITH', $qb->expr()->eq('obj.identifier', 'st1.object'))
            ->leftJoin($this->_entityName, 'st2', 'WITH', $qb->expr()->eq('obj.identifier', 'st2.actor'))
            ->leftJoin($this->_entityName, 'st3', 'WITH', $qb->expr()->eq('obj.identifier', 'st3.authority'))
            ->leftJoin(State::class, 'st', 'WITH', $qb->expr()->eq('obj.identifier', 'st.actor'))
            ->where($qb->expr()->isNull('st1.id'))
            ->andWhere($qb->expr()->isNull('st2.id'))
            ->andWhere($qb->expr()->isNull('st3.id'))
            ->andWhere($qb->expr()->isNull('st.registration'));

        $results = $qb->getQuery()->getArrayResult();

        if (!empty($results)) {

            $ids = [];
            foreach ($results as $id) {
                $ids[] = $id['identifier'];
            }

            $qb = $this->_em->createQueryBuilder();
            $qb
                ->delete(StatementObject::class, 'obj')
                ->where($qb->expr()->in('obj', ':ids'))
                ->setParameter('ids', $ids);
            $qb->getQuery()->execute();
        }
    }

    /**
     * @param $ids
     * @return mixed
     */
    public function removeStatements($ids)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb
            ->delete($this->_entityName, 'st')
            ->where($qb->expr()->in('st.id', ':ids'))
            ->setParameter('ids', $ids);

        return $qb->getQuery()->execute();
    }

    /**
     * @param Statement $statement
     * @param bool $flush
     * @return StatementId|void
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function storeStatement(Statement $statement, $flush = true)
    {
        /**
         * UUID
         */
        if (null === $statement->getId()) {
            if (class_exists('Entrili\XApiBundle\Model\Uuid')) {
                $uuid = ModelUuid::uuid4();
            } else {
                $uuid = RamseyUuid::uuid4();
            }

            $statement = $statement->withId(StatementId::fromUuid($uuid));
        }

        /**
         * Mapped Statement
         */
        $mappedStatement = MappedStatement::fromModel($statement);
        $mappedStatement->stored = time();

        /**
         * Check uniq Verb
         */
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('v')
            ->from('Entrili\XApiBundle\Repository\Mapping\Verb', 'v')
            ->where($qb->expr()->eq('v.id', ':id'))
            ->setParameter('id', $mappedStatement->verb->id);

        $verb = $qb->getQuery()->getOneOrNullResult();

        if (!is_null($verb)) {

            foreach ($mappedStatement->verb->display as $k => $v) {
                if (!array_key_exists($k, $verb->display)) {
                    $verb->display[$k] = $v;
                }
            }
            $mappedStatement->verb = $verb;
        }

        /**
         * Check Activity
         */
        if ($mappedStatement->object->activityId) {

            $hash = md5(
                $mappedStatement->object->activityId
                . json_encode($mappedStatement->object->activityName)
                . $mappedStatement->object->activityType
            );

            $qb = $this->_em->createQueryBuilder();
            $qb
                ->select('o')
                ->from('Entrili\XApiBundle\Repository\Mapping\StatementObject', 'o')
                ->where($qb->expr()->eq('o.hash', ':hash'))
                ->setParameter('hash', $hash);

            $activities = $qb->getQuery()->getResult();

            if (!empty($activities)) {

                foreach ($activities as $activity) {
                    $mappedStatement->object = $activity;
                    break;
                }
            }
        }

        /**
         * Check Context
         */
        if ($mappedStatement->context) {

            $qb = $this->_em->createQueryBuilder();
            $qb
                ->select('c')
                ->from('Entrili\XApiBundle\Repository\Mapping\Context', 'c')
                ->where($qb->expr()->eq('c.registration', ':id'))
                ->setParameter('id', $mappedStatement->context->registration->getId());

            $contexts = $qb->getQuery()->getResult();

            if (!empty($contexts)) {
                $registration = $mappedStatement->context->registration;
                foreach ($contexts as $context) {
                    $mappedStatement->context = $context;
                    $mappedStatement->context->registration = $registration;
                    break;
                }
            }
        }

        /**
         * Flush
         */
        if ($flush) {
            $this->_em->persist($mappedStatement);

            $this->flush();
        }
    }

    /**
     * @param MappedStatement $mappedStatement
     * @param bool $flush
     */
    public function updateStatement(MappedStatement $mappedStatement, $flush = true)
    {
        $this->_em->persist($mappedStatement);

        if ($flush) {
            $this->flush();
        }
    }
}
