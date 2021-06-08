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

use Entrili\XApiBundle\Model\StatementId;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Mathieu Boldo <mathieu.boldo@entrili.com>
 */
final class StatementOptionsController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function optionsStatement(Request $request)
    {
        if (null === $statementId = $request->query->get('statementId')) {
            //throw new BadRequestHttpException('Required statementId parameter is missing.');
        }

        try {
            $id = StatementId::fromString($statementId);
        } catch (\InvalidArgumentException $e) {
            //throw new BadRequestHttpException(sprintf('Parameter statementId ("%s") is not a valid UUID.', $statementId), $e);
        }

        return new Response('', 204);
    }
}
