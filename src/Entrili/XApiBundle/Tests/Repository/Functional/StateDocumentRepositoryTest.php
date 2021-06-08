<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Tests\Repository\Functional;

use Entrili\XApiBundle\Model\StateDocument;
use Entrili\XApiBundle\Model\StateDocumentsFilter;
use Entrili\XApiBundle\Repository\StateDocumentRepositoryInterface;
use Entrili\XApiBundle\Tests\Fixtures\Data\ActivityFixtures;
use Entrili\XApiBundle\Tests\Fixtures\Data\ActorFixtures;
use Entrili\XApiBundle\Tests\Fixtures\Data\DocumentFixtures;
use PHPUnit\Framework\TestCase;

/**
 * @author Jérôme Parmentier <jerome.parmentier@acensi.fr>
 */
abstract class StateDocumentRepositoryTest extends TestCase
{
    /**
     * @var StateDocumentRepositoryInterface
     */
    private $stateDocumentRepository;

    protected function setUp()
    {
        $this->stateDocumentRepository = $this->createStateDocumentRepositoryInterface();
        $this->cleanDatabase();
    }

    protected function tearDown()
    {
        $this->cleanDatabase();
    }

    /**
     * @expectedException \Entrili\XApiBundle\Exception\NotFoundException
     * @throws \Entrili\XApiBundle\Exception\NotFoundException
     */
    public function testFetchingNonExistingStateDocumentThrowsException()
    {
        $criteria = new StateDocumentsFilter();
        $criteria
            ->byActivity(ActivityFixtures::getIdActivity())
            ->byAgent(ActorFixtures::getTypicalAgent());

        $this->stateDocumentRepository->find('unknown-state-id', $criteria);
    }

    /**
     * @dataProvider getStateDocument
     * @param StateDocument $stateDocument
     * @throws \Entrili\XApiBundle\Exception\NotFoundException
     * @throws \Entrili\XApiBundle\Repository\Exception\SaveException
     */
    public function testCreatedStateDocumentCanBeRetrievedByOriginal(StateDocument $stateDocument)
    {
        $this->stateDocumentRepository->save($stateDocument);

        $criteria = new StateDocumentsFilter();
        $criteria
            ->byActivity($stateDocument->getState()->getActivity())
            ->byAgent($stateDocument->getState()->getAgent());

        $fetchedStateDocument = $this->stateDocumentRepository->find($stateDocument->getState()->getStateId(), $criteria);

        $this->assertEquals($stateDocument->getState()->getStateId(), $fetchedStateDocument->getState()->getStateId());
        $this->assertEquals($stateDocument->getState()->getRegistration(), $fetchedStateDocument->getState()->getRegistration());
        $this->assertTrue($stateDocument->getState()->getActivity()->equals($fetchedStateDocument->getState()->getActivity()));
        $this->assertTrue($stateDocument->getState()->getActor()->equals($fetchedStateDocument->getState()->getActor()));
        $this->assertEquals($stateDocument->getData(), $fetchedStateDocument->getData());
    }

    /**
     * @dataProvider getStateDocument
     * @expectedException \Entrili\XApiBundle\Exception\NotFoundException
     * @param StateDocument $stateDocument
     * @throws \Entrili\XApiBundle\Exception\NotFoundException
     * @throws \Entrili\XApiBundle\Repository\Exception\DeleteException
     * @throws \Entrili\XApiBundle\Repository\Exception\SaveException
     */
    public function testDeletedStateDocumentIsDeleted(StateDocument $stateDocument)
    {
        $this->stateDocumentRepository->save($stateDocument);
        $this->stateDocumentRepository->delete($stateDocument);

        $criteria = new StateDocumentsFilter();
        $criteria
            ->byActivity($stateDocument->getState()->getActivity())
            ->byAgent($stateDocument->getState()->getActor());

        $this->stateDocumentRepository->find($stateDocument->getState()->getStateId(), $criteria);
    }

    /**
     * @dataProvider getStateDocument
     * @param StateDocument $stateDocument
     * @throws \Entrili\XApiBundle\Exception\NotFoundException
     * @throws \Entrili\XApiBundle\Repository\Exception\DeleteException
     * @throws \Entrili\XApiBundle\Repository\Exception\SaveException
     */
    public function testCommitSaveDeferredStateDocument(StateDocument $stateDocument)
    {
        $this->stateDocumentRepository->saveDeferred($stateDocument);
        $this->stateDocumentRepository->commit();

        $criteria = new StateDocumentsFilter();
        $criteria
            ->byActivity($stateDocument->getState()->getActivity())
            ->byAgent($stateDocument->getState()->getActor());

        $fetchedStateDocument = $this->stateDocumentRepository->find($stateDocument->getState()->getStateId(), $criteria);

        $this->assertEquals($stateDocument->getState()->getStateId(), $fetchedStateDocument->getState()->getStateId());
        $this->assertEquals($stateDocument->getState()->getRegistration(), $fetchedStateDocument->getState()->getRegistration());
        $this->assertTrue($stateDocument->getState()->getActivity()->equals($fetchedStateDocument->getState()->getActivity()));
        $this->assertTrue($stateDocument->getState()->getActor()->equals($fetchedStateDocument->getState()->getActor()));
        $this->assertEquals($stateDocument->getData(), $fetchedStateDocument->getData());
    }

    /**
     * @dataProvider getStateDocument
     * @expectedException \Entrili\XApiBundle\Exception\NotFoundException
     * @param StateDocument $stateDocument
     * @throws \Entrili\XApiBundle\Exception\NotFoundException
     * @throws \Entrili\XApiBundle\Repository\Exception\DeleteException
     * @throws \Entrili\XApiBundle\Repository\Exception\SaveException
     */
    public function testCommitDeleteDeferredStateDocument(StateDocument $stateDocument)
    {
        $this->stateDocumentRepository->save($stateDocument);
        $this->stateDocumentRepository->deleteDeferred($stateDocument);
        $this->stateDocumentRepository->commit();

        $criteria = new StateDocumentsFilter();
        $criteria
            ->byActivity($stateDocument->getState()->getActivity())
            ->byAgent($stateDocument->getState()->getActor());

        $this->stateDocumentRepository->find($stateDocument->getState()->getStateId(), $criteria);
    }

    public function getStateDocument()
    {
        return array(DocumentFixtures::getStateDocument());
    }

    abstract protected function createStateDocumentRepositoryInterface();

    abstract protected function cleanDatabase();
}
