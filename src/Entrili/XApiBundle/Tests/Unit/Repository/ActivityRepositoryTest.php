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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Entrili\XApiBundle\Exception\NotFoundException;
use Entrili\XApiBundle\Exception\XApiException;
use Entrili\XApiBundle\Model\Activity;
use Entrili\XApiBundle\Model\IRI;
use Entrili\XApiBundle\Repository\Mapping\StatementObject as MappedObject;
use Entrili\XApiBundle\Tests\Fixtures\Data\ActivityFixtures;
use Entrili\XApiBundle\Tests\Fixtures\Data\ActorFixtures;
use Entrili\XApiBundle\Tests\Repository\ActivityRepository;
use Entrili\XApiBundle\Tests\Repository\Storage\ObjectStorage;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * @author Jérôme Parmentier <jerome.parmentier@acensi.fr>
 */
class ActivityRepositoryTest extends TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject|EntityManager $objectManager
     */
    private $objectManager;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject|ObjectStorage $objectStorage
     */
    private $objectStorage;

    /**
     * @var ActivityRepository
     */
    private $activityRepository;

    protected function setUp()
    {

        $this->objectManager = $this
            ->getMockBuilder(ObjectManager::class)
            ->getMock();

        $this->objectStorage = $this
            ->getMockBuilder(ObjectStorage::class)
            ->getMock();

        $repo = new \Entrili\XApiBundle\Repository\ActivityRepository($this->objectManager, new ClassMetadata(Activity::class));

        $this->activityRepository = new ActivityRepository($repo, $this->objectManager);
    }

    /**
     * @throws NotFoundException
     * @throws XApiException
     */
    public function testFindActivityById()
    {
        $activityId = IRI::fromString('http:///69Hbrav5YiX');

        $this
            ->objectStorage
            ->expects($this->once())
            ->method('findObject')
            ->with([
                'type'       => 'activity',
                'activityId' => $activityId->getValue(),
            ])
            ->will($this->returnValue(MappedObject::fromModel(ActivityFixtures::getIdActivity())));

        $this->activityRepository->findActivityById($activityId);
    }

    /**
     * @throws NotFoundException
     * @throws XApiException
     */
    public function testNotFoundObject()
    {
        $activityId = IRI::fromString('http:///69Hbrav5YiX');
        $this->expectException(NotFoundException::class);
        $this
            ->objectStorage
            ->expects($this->once())
            ->method('findObject')
            ->with([
                'type'       => 'activity',
                'activityId' => $activityId->getValue(),
            ])
            ->will($this->returnValue($this->getExpectedException()));

        $this->activityRepository->findActivityById($activityId);
    }

    /**
     * @throws NotFoundException
     * @throws XApiException
     */
    public function testObjectIsNotAnActivity()
    {
        $activityId = IRI::fromString('http:///69Hbrav5YiX');

        $this
            ->objectStorage
            ->expects($this->once())
            ->method('findObject')
            ->with([
                'type'       => 'activity',
                'activityId' => $activityId->getValue(),
            ])
            ->will($this->returnValue(MappedObject::fromModel(ActorFixtures::getMboxAgent())));

        $this->activityRepository->findActivityById($activityId);
    }
}
