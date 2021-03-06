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
use Entrili\XApiBundle\Model\IRI;
use Entrili\XApiBundle\Repository\ActivityRepositoryInterface;

/**
 * Activity repository clearing the object manager between read and write operations.
 *
 * @author Jérôme Parmentier <jerome.parmentier@acensi.fr>
 */
final class ActivityRepository implements ActivityRepositoryInterface
{
    private $repository;
    private $objectManager;

    public function __construct(ActivityRepositoryInterface $repository, ObjectManager $objectManager)
    {
        $this->repository = $repository;
        $this->objectManager = $objectManager;
    }

    /**
     * {@inheritdoc}
     */
    public function findActivityById(IRI $activityId)
    {
        $activity = $this->repository->findActivityById($activityId);
        $this->objectManager->clear();

        return $activity;
    }
}
