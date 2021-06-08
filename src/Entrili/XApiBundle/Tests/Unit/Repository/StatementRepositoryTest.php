<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Tests\Unit\Repository;

use Doctrine\Common\Persistence\ObjectManager;
use Entrili\XApiBundle\Model\StatementId;
use Entrili\XApiBundle\Model\StatementsFilter;
use Entrili\XApiBundle\Model\Uuid as ModelUuid;
use Entrili\XApiBundle\Repository\Mapping\Statement as MappedStatement;
use Entrili\XApiBundle\Repository\StatementRepository;
use Entrili\XApiBundle\Tests\Fixtures\Data\StatementFixtures;
use Entrili\XApiBundle\Tests\Fixtures\Data\VerbFixtures;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;


/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class StatementRepositoryTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Entrili\XApiBundle\Repository\StatementRepository
     */
    private $mappedStatementRepository;

    /**
     * @var StatementRepository
     */
    private $repository;

    /**
     * @var $objectManager
     */
    private $objectManager;

    protected function setUp()
    {
        $this->mappedStatementRepository = $this->createMappedStatementRepositoryMock();
        $this->objectManager = $this->createObjectManager();
        $this->repository = $this->createRepository();
    }

    /**
     * @throws \Entrili\XApiBundle\Exception\NotFoundException
     */
    public function testFindStatementById()
    {
        if (class_exists('Entrili\XApiBundle\Model\Uuid')) {
            $statementId = StatementId::fromUuid(ModelUuid::uuid4());
        } else {
            $statementId = StatementId::fromUuid(RamseyUuid::uuid4());
        }

        $this
            ->mappedStatementRepository
            ->expects($this->once())
            ->method('findStatement')
            ->with(array('id' => $statementId->getValue()))
            ->will($this->returnValue(MappedStatement::fromModel(StatementFixtures::getMinimalStatement())));

        $this->repository->findStatementById($statementId);
    }

    public function testFindStatementsByCriteria()
    {
        $verb = VerbFixtures::getTypicalVerb();

        $this
            ->mappedStatementRepository
            ->expects($this->once())
            ->method('findStatements')
            ->with($this->equalTo(array('verb' => $verb->getId()->getValue())))
            ->will($this->returnValue(array()));

        $filter = new StatementsFilter();
        $filter->byVerb($verb);
        $this->repository->findStatementsBy($filter);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testSave()
    {
        $statement = StatementFixtures::getMinimalStatement();
        $this
            ->mappedStatementRepository
            ->expects($this->once())
            ->method('storeStatement')
            ->with(
                $this->callback(function (MappedStatement $mappedStatement) use ($statement) {
                    $expected = MappedStatement::fromModel($statement);
                    $actual = clone $mappedStatement;
                    $actual->stored = null;

                    return $expected == $actual;
                }),
                true
            );

        $this->repository->storeStatement($statement);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testSaveWithoutFlush()
    {
        $statement = StatementFixtures::getMinimalStatement();
        $this
            ->mappedStatementRepository
            ->expects($this->once())
            ->method('storeStatement')
            ->with(
                $this->callback(function (MappedStatement $mappedStatement) use ($statement) {
                    $expected = MappedStatement::fromModel($statement);
                    $actual = clone $mappedStatement;
                    $actual->stored = null;

                    return $expected == $actual;
                }),
                false
            );

        $this->repository->storeStatement($statement, false);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Entrili\XApiBundle\Repository\StatementRepository
     */
    protected function createMappedStatementRepositoryMock()
    {
        return $this
            ->getMockBuilder('\Entrili\XApiBundle\Repository\StatementRepository')
            ->getMock();
    }

    protected function cleanDatabase()
    {
        foreach ($this->repository->findStatements(array()) as $statement) {
            $this->objectManager->remove($statement);
        }

        $this->objectManager->flush();
    }

    /**
     * @return ObjectManager
     */
    abstract protected function createObjectManager();

    /**
     * @return string
     */
    abstract protected function getStatementClassName();

    /**
     * @return mixed
     */
    private function createRepository()
    {
        return $this->objectManager->getRepository($this->getStatementClassName());
    }
}
