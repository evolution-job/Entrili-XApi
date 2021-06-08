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
use Entrili\XApiBundle\Exception\NotFoundException;
use Entrili\XApiBundle\Exception\XApiException;
use Entrili\XApiBundle\Model\Activity;
use Entrili\XApiBundle\Model\IRI;

/**
 * Doctrine based {@link Activity} repository.
 *
 * @author Jérôme Parmentier <jerome.parmentier@acensi.fr>
 */
final class ActivityRepository extends EntityRepository implements ActivityRepositoryInterface
{

    /**
     * @param IRI $activityId
     * @return Activity
     * @throws NotFoundException
     * @throws XApiException
     */
    public function findActivityById(IRI $activityId)
    {

        $mappedObject = parent::find('Entrili\XApiBundle\Repository\Mapping\StatementObject', $activityId->getValue());

        if (null === $mappedObject) {
            throw new NotFoundException(sprintf('No activity could be found matching the ID "%s".', $activityId->getValue()));
        }

        $activity = $mappedObject->getModel();

        if (!$activity instanceof Activity) {
            throw new XApiException(sprintf('2. No activity could be found matching the ID "%s".', $activityId->getValue()));
        }

        return $activity;
    }
}
