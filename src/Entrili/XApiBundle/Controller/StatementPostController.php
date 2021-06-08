<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Entrili\XApiBundle\Controller;

use Entrili\XApiBundle\Exception\NotFoundException;
use Entrili\XApiBundle\Model\Statement;
use Entrili\XApiBundle\Repository\StatementRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * @author Jérôme Parmentier <jerome.parmentier@acensi.fr>
 * @author Mathieu Boldo <mathieu.boldo@entrili.com>
 */
final class StatementPostController
{
    private $repository;

    public function __construct(StatementRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $statement
     * @return JsonResponse
     */
    public function postStatement(array $statement): JsonResponse
    {
        $uuids = [];

        /**
         * @var Statement $statementObject
         */
        foreach ($statement as $statementObject) {

            try {
                $existingStatement = $this->repository->findStatementById($statementObject->getId());

                if (!$existingStatement->equals($statementObject)) {
                    throw new ConflictHttpException('The new statement is not equal to an existing statement with the same id.');
                }
            } catch (NotFoundException $e) {

                $statementObject->getContext()->setRegistration($statementObject->getContext()->getRegistration());

                $this->repository->storeStatement($statementObject, true);
            }

            $uuids[] = $statementObject->getId();
        }

        return new JsonResponse($uuids, 200);
    }
}
